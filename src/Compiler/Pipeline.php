<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Context\ContextInterface;
use Railt\SDL\Compiler\Context\GlobalContext;
use Railt\SDL\Compiler\Context\GlobalContextInterface;
use Railt\SDL\Compiler\Context\LocalContextInterface;
use Railt\SDL\Compiler\Context\ProvidesTypes;
use Railt\SDL\Compiler\Pipeline\HeapInterface;
use Railt\SDL\Compiler\Pipeline\StackHeap;
use Railt\SDL\Compiler\Record\DirectiveDefinitionRecord;
use Railt\SDL\Compiler\Record\EnumDefinitionRecord;
use Railt\SDL\Compiler\Record\ExtensionRecord;
use Railt\SDL\Compiler\Record\InputDefinitionRecord;
use Railt\SDL\Compiler\Record\InterfaceDefinitionRecord;
use Railt\SDL\Compiler\Record\InvocationRecord;
use Railt\SDL\Compiler\Record\NamespaceDefinitionRecord;
use Railt\SDL\Compiler\Record\ObjectDefinitionRecord;
use Railt\SDL\Compiler\Record\RecordInterface;
use Railt\SDL\Compiler\Record\ScalarDefinitionRecord;
use Railt\SDL\Compiler\Record\SchemaDefinitionRecord;
use Railt\SDL\Compiler\Record\UnionDefinitionRecord;
use Railt\SDL\Compiler\System\CompleteContextSystem;
use Railt\SDL\Compiler\System\CreateContextSystem;
use Railt\SDL\Compiler\System\ExportInnerTypesSystem;
use Railt\SDL\Compiler\System\LinkerSystem;
use Railt\SDL\Compiler\System\SystemInterface;
use Railt\SDL\Compiler\System\TypeRegisterSystem;
use Railt\SDL\Exception\BadAstMappingException;
use Railt\SDL\Parser\Factory;
use Railt\SDL\Stack\CallStack;

/**
 * Class Pipeline
 */
class Pipeline implements PipelineInterface
{
    /**
     * @var int[]
     */
    private const DEFINITIONS = [
        '#DirectiveDefinition' => DirectiveDefinitionRecord::class,
        '#EnumDefinition'      => EnumDefinitionRecord::class,
        '#InputDefinition'     => InputDefinitionRecord::class,
        '#InterfaceDefinition' => InterfaceDefinitionRecord::class,
        '#NamespaceDefinition' => NamespaceDefinitionRecord::class,
        '#ObjectDefinition'    => ObjectDefinitionRecord::class,
        '#ScalarDefinition'    => ScalarDefinitionRecord::class,
        '#SchemaDefinition'    => SchemaDefinitionRecord::class,
        '#UnionDefinition'     => UnionDefinitionRecord::class,
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
     * @var Factory
     */
    private $parser;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var SystemInterface[]|array
     */
    private $before = [];

    /**
     * @var SystemInterface[]|array
     */
    private $after = [];

    /**
     * @var GlobalContextInterface
     */
    private $context;

    /**
     * @var HeapInterface
     */
    private $heap;

    /**
     * @var HeapInterface
     */
    private $future;

    /**
     * HeadingsTable constructor.
     * @param CallStack $stack
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __construct(CallStack $stack)
    {
        $this->stack   = $stack;
        $this->parser  = Factory::create();
        $this->context = new GlobalContext($stack);
        $this->heap    = new StackHeap();
        $this->future  = new StackHeap();

        $this->before(new CreateContextSystem());
        $this->before(new TypeRegisterSystem());
        $this->before(new ExportInnerTypesSystem($this));
        $this->before(new CompleteContextSystem());
        $this->after(new LinkerSystem($this->stack));
    }

    /**
     * @param SystemInterface $system
     */
    public function before(SystemInterface $system): void
    {
        $this->before[] = $system;
    }

    /**
     * @param SystemInterface $system
     */
    public function after(SystemInterface $system): void
    {
        $this->after[] = $system;
    }

    /**
     * @param Readable $file
     * @return ProvidesTypes
     * @throws BadAstMappingException
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \Railt\SDL\Exception\LossOfStackException
     * @throws \RuntimeException
     */
    public function read(Readable $file): ProvidesTypes
    {
        $this->insertFile($file);

        foreach ($this->future as $record) {
            $this->stack->pushRecord($record);

            foreach ($this->after as $system) {
                $system->provide($record);
            }

            $this->stack->pop();
        }

        return $this->context->getTypes();
    }

    /**
     * @param Readable $file
     * @throws BadAstMappingException
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \Railt\SDL\Exception\LossOfStackException
     * @throws \RuntimeException
     */
    public function insertFile(Readable $file): void
    {
        $current = $this->context->create(null, $file);

        $this->context->push($current);

        foreach ($this->parse($file)->getChildren() as $rule) {
            $this->insertAst($file, $rule);
            $this->chunk();
        }
    }

    /**
     * @param Readable $file
     * @return RuleInterface|RuleInterface
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    private function parse(Readable $file): NodeInterface
    {
        $result = $this->parser->parse($file);

        \assert($result instanceof RuleInterface);

        return $result;
    }

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @throws BadAstMappingException
     * @throws \Railt\SDL\Exception\LossOfStackException
     */
    public function insertAst(Readable $file, RuleInterface $ast): void
    {
        /** @var LocalContextInterface $context */
        $context = $this->context->current();

        $this->stack->pushAst($file, $ast);
        $this->insert($this->getRecord($ast, $context));
        $this->stack->pop();
    }

    /**
     * @param RecordInterface $record
     */
    public function insert(RecordInterface $record): void
    {
        $this->heap->push($record);
    }

    /**
     * @param RuleInterface $rule
     * @param ContextInterface $context
     * @return RecordInterface
     * @throws BadAstMappingException
     */
    private function getRecord(RuleInterface $rule, ContextInterface $context): RecordInterface
    {
        $class = self::DEFINITIONS[$rule->getName()] ?? null;

        if ($class) {
            return new $class($context, $rule);
        }

        $error = \sprintf('Undefined abstract syntax tree production <%s>', \trim($rule->getName(), '#'));
        throw new BadAstMappingException($error, $this->stack);
    }

    /**
     * @return void
     * @throws \Railt\SDL\Exception\LossOfStackException
     */
    private function chunk(): void
    {
        $size = 0;

        foreach ($this->heap as $record) {
            $this->stack->pushRecord($record);
            ++$size;

            foreach ($this->before as $system) {
                $system->provide($record);
            }

            $this->future->push($record);
        }

        while ($size-- > 0) {
            $this->stack->pop();
        }
    }
}
