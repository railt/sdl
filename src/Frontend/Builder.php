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
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\InternalException;
use Railt\SDL\Frontend;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Context\GlobalContext;
use Railt\SDL\Frontend\Context\GlobalContextInterface;
use Railt\SDL\IR\SymbolTable;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\SymbolTableInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var string[]|Builder\BuilderInterface[]
     */
    private const DEFAULT_BUILDER_DEFINITIONS = [
        Builder\Instruction\ImportBuilder::class,
        Builder\Instruction\NamespaceBuilder::class,
        Builder\Instruction\VariableBuilder::class,
        Builder\Instruction\VariableReassigmentBuilder::class,

        //
        Builder\DefinitionBuilder::class,

        // Values
        Builder\Value\ScalarValueBuilder::class,
        Builder\Value\VariableValueBuilder::class,
        Builder\Value\TypeInvocationBuilder::class,
        Builder\Value\ConstantValueBuilder::class,
        Builder\Value\BooleanValueBuilder::class,

        //
        Builder\Common\TypeNameBuilder::class,
        Builder\TypeDefinitionBuilder::class,
    ];

    /**
     * @var array|Builder\BuilderInterface[]
     */
    private $builders = [];

    /**
     * @var Frontend
     */
    private $frontend;

    /**
     * @var SymbolTableInterface
     */
    private $table;

    /**
     * Builder constructor.
     * @param Frontend $frontend
     * @param SymbolTable $table
     */
    public function __construct(Frontend $frontend, SymbolTable $table)
    {
        $this->table    = $table;
        $this->frontend = $frontend;
        $this->bootDefaults();
    }

    /**
     * @return void
     */
    private function bootDefaults(): void
    {
        foreach (self::DEFAULT_BUILDER_DEFINITIONS as $builder) {
            $this->builders[] = new $builder($this);
        }
    }

    /**
     * @param Readable $readable
     * @return \Traversable
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\SDL\Exception\SyntaxException
     */
    public function load(Readable $readable): iterable
    {
        return $this->frontend->load($readable);
    }

    /**
     * @param Readable $file
     * @param iterable $ast
     * @return iterable
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(Readable $file, iterable $ast): iterable
    {
        $context = new GlobalContext($file, $this->table);

        foreach ($ast as $child) {
            if ($this->filter([$context, $result] = $this->reduce($context, $child))) {
                yield $result;
            }
        }


        echo \str_repeat('=', 60) . "\n";
        echo \sprintf('| %5s | %-48s |', 'ID', 'VARIABLE') . "\n";
        echo \str_repeat('-', 60) . "\n";
        foreach ($this->table as $id => $var) {
            echo \sprintf('| %5d | %-48s |', $id, $var) . "\n";
        }
        echo \str_repeat('=', 60) . "\n";
    }

    /**
     * @param mixed $result
     * @return bool
     */
    private function filter($result): bool
    {
        return false;
    }

    /**
     * @param ContextInterface $context
     * @param RuleInterface $ast
     * @return array|iterable<int,ContextInterface|mixed>
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function reduce(ContextInterface $context, RuleInterface $ast): array
    {
        try {
            $process = $this->resolve($context, $ast);

            if ($process instanceof \Generator) {
                return $this->run($context, $process);
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
     * @return array|iterable<int, ContextInterface|mixed>
     */
    private function run(ContextInterface $ctx, \Generator $process): array
    {
        while ($process->valid()) {
            try {
                $value = $process->current();

                switch (true) {
                    case $value instanceof ContextInterface:
                        $ctx = $value;
                        break;

                    case $value instanceof RuleInterface:
                        [$ctx, $value] = $this->reduce($ctx, $value);
                        break;

                    case $value instanceof ValueInterface:
                        $value = $ctx->declare($process->key(), $value);
                        break;

                    case \is_string($value):
                        $value = $ctx->fetch($value);
                        break;
                }

                $process->send($value);
            } catch (\Throwable $e) {
                $process->throw($e);
            }
        }

        return [$ctx, $process->getReturn()];
    }
}
