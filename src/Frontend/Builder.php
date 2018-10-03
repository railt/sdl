<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\Exception\UnrecognizedTokenException;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\InternalException;
use Railt\SDL\Exception\SyntaxException;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Context\GlobalContext;
use Railt\SDL\Frontend\Deferred\Deferred;
use Railt\SDL\Frontend\Deferred\DeferredInterface;
use Railt\SDL\Frontend\Deferred\Identifiable;
use Railt\SDL\Frontend\Deferred\Storage;
use Railt\SDL\IR\SymbolTable;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\SymbolTableInterface;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var string[]|Builder\BuilderInterface[]
     */
    private const DEFAULT_BUILDER_DEFINITIONS = [
        //
        // Instruction builders.
        // Provides variadic result.
        //
        Builder\Instruction\ImportBuilder::class,
        Builder\Instruction\NamespaceBuilder::class,
        Builder\Instruction\VariableBuilder::class,
        Builder\Instruction\VariableReassigmentBuilder::class,

        //
        // Definition builders.
        // Provides deferred builders.
        //
        Builder\Definition\ObjectDefinitionBuilder::class,
        Builder\Definition\SchemaDefinitionBuilder::class,

        //
        // Value builders.
        // Provides: \Railt\SDL\IR\SymbolTable\ValueInterface
        //
        Builder\Value\ScalarValueBuilder::class,
        Builder\Value\VariableValueBuilder::class,
        Builder\Value\TypeInvocationBuilder::class,
        Builder\Value\ConstantValueBuilder::class,
        Builder\Value\BooleanValueBuilder::class,

        //
        // Common builders.
        // Provides:
        //  - TypeNameBuilder: Railt\SDL\IR\Type\TypeNameInterface
        //  - ConstantNameBuilder: Railt\SDL\IR\Type\TypeNameInterface
        //  - TypeDefinitionBuilder: Railt\SDL\Frontend\Definition\DefinitionInterface
        //
        Builder\Common\TypeNameBuilder::class,
        Builder\Common\ConstantNameBuilder::class,
        Builder\Common\TypeDefinitionBuilder::class,
    ];

    /**
     * @var array|Builder\BuilderInterface[]
     */
    private $builders = [];

    /**
     * @var Storage
     */
    private $store;

    /**
     * @var SymbolTableInterface
     */
    private $table;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        $this->parser = new Parser();
        $this->store = new Storage();
        $this->table = new SymbolTable();

        $this->bootDefaults();
    }

    /**
     * @return void
     */
    private function bootDefaults(): void
    {
        foreach (self::DEFAULT_BUILDER_DEFINITIONS as $builder) {
            $this->builders[] = new $builder($this, $this->store);
        }
    }

    /**
     * @param Readable $readable
     * @return array[]|iterable
     * @throws SyntaxException
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function buildFile(Readable $readable): iterable
    {
        $ast = $this->parse($readable);

        return $this->buildAst($readable, $ast);
    }

    /**
     * Parse the file using top-down parser and
     * return the Abstract Syntax Tree.
     *
     * @param Readable $file
     * @return RuleInterface
     * @throws SyntaxException
     */
    public function parse(Readable $file): RuleInterface
    {
        try {
            return $this->parser->parse($file);
        } catch (UnexpectedTokenException | UnrecognizedTokenException $e) {
            $error = new SyntaxException($e->getMessage(), $e->getCode());
            $error->throwsIn($file, $e->getLine(), $e->getColumn());

            throw $error;
        }
    }

    /**
     * @param Readable $readable
     * @param iterable $ast
     * @return iterable|array[]
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function buildAst(Readable $readable, iterable $ast): iterable
    {
        $context = new GlobalContext($readable, $this->table);

        foreach ($ast as $child) {
            [$context, $result] = $this->buildNode($context, $child);

            yield [$context, $result];
        }

        yield from $this->after($context);
    }

    /**
     * @param ContextInterface $context
     * @param RuleInterface $ast
     * @return array|iterable<int,ContextInterface|mixed>
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function buildNode(ContextInterface $context, RuleInterface $ast): array
    {
        try {
            $process = $this->resolve($context, $ast);

            if ($process instanceof \Generator) {
                return $this->coroutine($context, $process, $ast->getOffset());
            }

            return [$context, $process];
        } catch (CompilerException $e) {
            throw $e->throwsIn($context->getFile(), $ast->getOffset());
        }
    }

    /**
     * @param ContextInterface $context
     * @param RuleInterface $ast
     * @return mixed|\Traversable|void
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function resolve(ContextInterface $context, RuleInterface $ast)
    {
        foreach ($this->builders as $builder) {
            if ($builder->match($ast)) {
                return $builder->reduce($context, $ast);
            }
        }

        $error = \sprintf('Unrecognized rule %s in (%s)', $ast->getName(), $ast);
        throw (new InternalException($error))->throwsIn($context->getFile(), $ast->getOffset());
    }

    /**
     * @param ContextInterface $ctx
     * @param \Generator $process
     * @param int $offset
     * @return array|iterable<int, ContextInterface|mixed>
     */
    private function coroutine(ContextInterface $ctx, \Generator $process, int $offset = 0): array
    {
        while ($process->valid()) {
            try {
                $value = $process->current();

                switch (true) {
                    case $value instanceof ContextInterface:
                        $ctx = $value;
                        break;

                    case $value instanceof RuleInterface:
                        [$ctx, $value] = $this->buildNode($ctx, $value);
                        break;

                    case $value instanceof ValueInterface:
                        $value = $ctx->declare($process->key(), $value);
                        break;

                    case \is_string($value):
                        $value = $ctx->fetch($value);
                        break;

                    /** @noinspection PhpMissingBreakStatementInspection */
                    case $value instanceof \Closure:
                        $value = new Deferred($ctx, $value);

                    case $value instanceof DeferredInterface:
                        /** @noinspection SuspiciousAssignmentsInspection */
                        $value = $this->store->add($value);

                        if (! $value->getOffset()) {
                            $value->definedIn($offset);
                        }

                        break;
                }

                $process->send($value);
            } catch (CompilerException $e) {
                $process->throw($e->throwsIn($ctx->getFile(), $offset));
            } catch (\Throwable $e) {
                $process->throw($e);
            }
        }

        return [$ctx, $process->getReturn()];
    }

    /**
     * @param ContextInterface $context
     * @return \Generator
     * @throws CompilerException
     */
    private function after(ContextInterface $context): \Generator
    {
        $after = $this->store->extract(function (DeferredInterface $deferred): bool {
            return ! $deferred instanceof Identifiable || ! $deferred->getDefinition()->isGeneric();
        });

        foreach ($after as $deferred) {
            try {
                yield $this->coroutine($context, $deferred->invoke());
            } catch (CompilerException $e) {
                throw $e->throwsIn($context->getFile(), $deferred->getOffset());
            }
        }
    }
}
