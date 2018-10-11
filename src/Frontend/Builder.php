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
use Railt\SDL\Exception\InternalException;
use Railt\SDL\Exception\SyntaxException;
use Railt\SDL\Frontend\Builder\BuilderInterface;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Context\GlobalContext;
use Railt\SDL\Frontend\Deferred\DeferredCollection as DeferredStorage;
use Railt\SDL\Frontend\Definition\DefinitionInterface;
use Railt\SDL\Frontend\Definition\Invocation;
use Railt\SDL\Frontend\Definition\Storage as TypesStorage;
use Railt\SDL\Frontend\Interceptor;
use Railt\SDL\Frontend\Interceptor\Factory;
use Railt\SDL\IR\SymbolTable;
use Railt\SDL\IR\SymbolTableInterface;
use Railt\SDL\Naming\StrategyInterface;

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
        Builder\Value\NullValueBuilder::class,
        Builder\Value\VariableValueBuilder::class,
        Builder\Value\TypeInvocationBuilder::class,
        Builder\Value\ConstantValueBuilder::class,
        Builder\Value\BooleanValueBuilder::class,
        Builder\Value\NumberValueBuilder::class,
        Builder\Value\StringValueBuilder::class,

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
    private $builders;

    /**
     * @var DeferredStorage
     */
    private $deferred;

    /**
     * @var SymbolTableInterface
     */
    private $table;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Process
     */
    private $process;

    /**
     * @var TypesStorage
     */
    private $types;

    /**
     * Builder constructor.
     * @param StrategyInterface $naming
     */
    public function __construct(StrategyInterface $naming)
    {
        $this->parser = new Parser();
        $this->table = new SymbolTable();
        $this->types = new TypesStorage($naming);
        $this->deferred = new DeferredStorage();

        $factory = $this->bootInterceptors($this->deferred, $this->types);

        $this->process = $this->bootProcess($factory);
        $this->builders = $this->bootBuilders();
    }

    /**
     * @param Factory $interceptors
     * @return Process
     */
    private function bootProcess(Factory $interceptors): Process
    {
        return new Process($interceptors);
    }

    /**
     * @param DeferredStorage $deferred
     * @param TypesStorage $types
     * @return Factory
     */
    private function bootInterceptors(DeferredStorage $deferred, TypesStorage $types): Factory
    {
        $wantsBuild = function (ContextInterface $ctx, RuleInterface $ast) {
            return $this->buildNode($ctx, $ast);
        };

        return new Factory([
            new Interceptor\ContextInterceptor(),
            new Interceptor\InvocationInterceptor($types),
            new Interceptor\DefinitionInterceptor($types),
            new Interceptor\RuleInterceptor($wantsBuild),
            new Interceptor\CallbackInterceptor($deferred),
            new Interceptor\DeferredInterceptor($deferred),
        ]);
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return array
     */
    public function buildNode(ContextInterface $ctx, RuleInterface $rule): array
    {
        $result = $this->runBuilder($ctx, $rule);

        return $this->process->run($ctx, $result);
    }

    /**
     * @param ContextInterface $context
     * @param RuleInterface $ast
     * @return mixed|\Traversable
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function runBuilder(ContextInterface $context, RuleInterface $ast)
    {
        return $this->getBuilder($context, $ast)->reduce($context, $ast);
    }

    /**
     * @param ContextInterface $context
     * @param RuleInterface $ast
     * @return BuilderInterface
     */
    private function getBuilder(ContextInterface $context, RuleInterface $ast): BuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->match($ast)) {
                return $builder;
            }
        }

        $error = \sprintf('Unrecognized rule %s in (%s)', $ast->getName(), $ast);
        throw (new InternalException($error))->throwsIn($context->getFile(), $ast->getOffset());
    }

    /**
     * @return array
     */
    private function bootBuilders(): array
    {
        $builders = [];

        foreach (self::DEFAULT_BUILDER_DEFINITIONS as $builder) {
            $builders[] = new $builder();
        }

        return $builders;
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
     * @param iterable|RuleInterface[] $rules
     * @return iterable|array[]
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function buildAst(Readable $readable, iterable $rules): iterable
    {
        $context = new GlobalContext($readable, $this->table);

        $context = $this->run($context, $rules);

        $context = $this->deferred($context);

        return [];
    }

    /**
     * @param ContextInterface $context
     * @param iterable|RuleInterface[] $rules
     * @return ContextInterface
     */
    private function run(ContextInterface $context, iterable $rules): ContextInterface
    {
        foreach ($rules as $child) {
            [$context] = $this->buildNode($context, $child);
        }

        return $context;
    }

    /**
     * @param ContextInterface $context
     * @return ContextInterface
     */
    private function deferred(ContextInterface $context): ContextInterface
    {
        $this->deferred->attach($this->types->export(function (DefinitionInterface $definition): bool {
            $invocation = new Invocation($definition->getName(), $definition->getContext());

            return ! $definition->isGeneric() && ! $this->types->resolved($invocation);
        }));

        [$context] = $this->process->run($context, $this->deferred->getIterator());

        return $context;
    }
}
