<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker;

use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Exception\BadAstMappingException;
use Railt\SDL\Exception\TypeConflictException;
use Railt\SDL\Linker\Record\BaseRecord;
use Railt\SDL\Linker\Record\DefinitionRecord;
use Railt\SDL\Linker\Record\ExtensionRecord;
use Railt\SDL\Linker\Record\InvocationRecord;
use Railt\SDL\Linker\Record\NamespaceDefinitionRecord;
use Railt\SDL\Linker\Record\ProvidesContext;
use Railt\SDL\Linker\Record\ProvidesDefinitions;
use Railt\SDL\Linker\Record\ProvidesName;
use Railt\SDL\Linker\Record\ProvidesPriority;
use Railt\SDL\Linker\Record\ProvidesRelations;
use Railt\SDL\Linker\Record\SchemaDefinitionRecord;
use Railt\SDL\Linker\Record\TypeDefinitionRecord;
use Railt\SDL\Parser\Factory;
use Railt\SDL\Stack\CallStack;

/**
 * Class HeadingsTable
 */
class HeadingsTable
{
    /**
     * @var int[]
     */
    private const DEFINITIONS = [
        '#DirectiveDefinition' => DefinitionRecord::class,
        '#EnumDefinition'      => DefinitionRecord::class,
        '#InputDefinition'     => DefinitionRecord::class,
        '#InterfaceDefinition' => DefinitionRecord::class,
        '#NamespaceDefinition' => NamespaceDefinitionRecord::class,
        '#ObjectDefinition'    => TypeDefinitionRecord::class,
        '#ScalarDefinition'    => DefinitionRecord::class,
        '#SchemaDefinition'    => SchemaDefinitionRecord::class,
        '#UnionDefinition'     => DefinitionRecord::class,
        '#EnumExtension'       => ExtensionRecord::class,
        '#InputExtension'      => ExtensionRecord::class,
        '#InterfaceExtension'  => ExtensionRecord::class,
        '#ObjectExtension'     => ExtensionRecord::class,
        '#ScalarExtension'     => ExtensionRecord::class,
        '#SchemaExtension'     => ExtensionRecord::class,
        '#UnionExtension'      => ExtensionRecord::class,
        '#Directive'           => InvocationRecord::class,
    ];

    /**
     * @var array
     */
    private $definitions = [];

    /**
     * @var \SplPriorityQueue
     */
    private $records;

    /**
     * @var Factory
     */
    private $parser;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var Context
     */
    private $context;

    /**
     * HeadingsTable constructor.
     * @param CallStack $stack
     */
    public function __construct(CallStack $stack)
    {
        $this->stack  = $stack;
        $this->parser = Factory::create();

        $this->context = new Context($stack);
        $this->records = new \SplPriorityQueue();
    }

    /**
     * @param Readable $file
     * @return \Traversable
     */
    public function extract(Readable $file): \Traversable
    {
        $ast = $this->parse($file);

        /** @var RuleInterface $rule */
        foreach ($ast->getChildren() as $rule) {
            $this->analyze($file, $rule);
        }

        yield from $this->records;
    }

    /**
     * @param Readable $file
     * @return RuleInterface|NodeInterface
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    private function parse(Readable $file): RuleInterface
    {
        return $this->parser->parse($file);
    }

    /**
     * @param Readable $file
     * @param RuleInterface $rule
     */
    private function analyze(Readable $file, RuleInterface $rule): void
    {
        $this->stack->pushAst($file, $rule);

        $this->context($this->record($file, $rule), function (BaseRecord $record): void {
            $this->bootChildren($record);
            $this->bootRelations($record);
            $this->bootPriority($record);
        });

        $this->stack->pop();
    }

    /**
     * @param BaseRecord $record
     * @param \Closure $then
     */
    private function context(BaseRecord $record, \Closure $then): void
    {
        if ($record instanceof ProvidesName) {
            $name = $this->context->resolve($record);

            if (\array_key_exists($name, $this->definitions)) {
                $previous = $this->definitions[$name];
                $this->stack->pushAst($previous->getFile(), $previous->getAst());

                $error = 'Can not register type %s because the name is already registered before';
                throw new TypeConflictException(\sprintf($error, $name), $this->stack);
            }

            $this->definitions[$name] = $record;
        }

        if ($record instanceof ProvidesContext) {
            $this->context->push($record);
        }

        $then($record);

        if ($record instanceof ProvidesContext) {
            $this->context->complete($record);
        }
    }

    /**
     * @param Readable $file
     * @param RuleInterface $rule
     * @return BaseRecord
     */
    private function record(Readable $file, RuleInterface $rule): BaseRecord
    {
        if (! \array_key_exists($rule->getName(), static::DEFINITIONS)) {
            $error = \sprintf('Unprocessable AST Node %s', $rule->getName());
            throw new BadAstMappingException($error, $this->stack);
        }

        $class = static::DEFINITIONS[$rule->getName()];

        return new $class($file, $rule, $this->stack);
    }

    /**
     * @param BaseRecord $record
     */
    private function bootChildren(BaseRecord $record): void
    {
        if ($record instanceof ProvidesDefinitions) {
            foreach ($record->getDefinitions() as $ast) {
                $this->analyze($record->getFile(), $ast);
            }
        }
    }

    /**
     * @param BaseRecord $record
     */
    private function bootRelations(BaseRecord $record): void
    {
        if ($record instanceof ProvidesRelations) {
            foreach ($record->getRelations() as $relation) {
                $this->fetch($relation);
            }
        }
    }

    private function fetch(string $type): void
    {
    }

    /**
     * @param BaseRecord $record
     */
    private function bootPriority(BaseRecord $record): void
    {
        $priority = $record instanceof ProvidesPriority ? $record->getPriority() : 0;

        $this->records->insert($record, $priority);
    }
}
