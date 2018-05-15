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
use Railt\SDL\Compiler\Context\ProvidesTypes;
use Railt\SDL\Compiler\Record\DefinitionRecord;
use Railt\SDL\Compiler\Record\ExtensionRecord;
use Railt\SDL\Compiler\Record\InvocationRecord;
use Railt\SDL\Compiler\Record\NamespaceDefinitionRecord;
use Railt\SDL\Compiler\Record\RecordInterface;
use Railt\SDL\Compiler\System\CreateContextSystem;
use Railt\SDL\Compiler\System\CompleteContextSystem;
use Railt\SDL\Compiler\System\SystemInterface;
use Railt\SDL\Compiler\System\TypeRegisterSystem;
use Railt\SDL\Exception\BadAstMappingException;
use Railt\SDL\Parser\Factory;
use Railt\SDL\Stack\CallStack;

/**
 * Class Pipeline
 */
class Pipeline implements PrebuiltTypes
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
        '#ObjectDefinition'    => DefinitionRecord::class,
        '#ScalarDefinition'    => DefinitionRecord::class,
        '#SchemaDefinition'    => DefinitionRecord::class,
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
    private $systems = [];

    /**
     * @var GlobalContextInterface
     */
    private $context;

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

        $this->addSystem(new CreateContextSystem());
        $this->addSystem(new TypeRegisterSystem());
        $this->addSystem(new CompleteContextSystem());
    }

    /**
     * @param SystemInterface $system
     */
    public function addSystem(SystemInterface $system): void
    {
        $this->systems[] = $system;
    }

    /**
     * @param Readable $file
     * @return ProvidesTypes|RecordInterface[]
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    public function extract(Readable $file): ProvidesTypes
    {
        $current = $this->context->create($file);

        $this->context->push($current);

        $this->process($file, $this->parse($file));

        return $current->getTypes();
    }

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     */
    private function process(Readable $file, RuleInterface $ast): void
    {
        foreach ($ast->getChildren() as $rule) {
            $context = $this->context->current();

            // AST Production to Record
            $this->stack->pushAst($file, $rule);
            $record = $this->getRecord($rule, $context);

            // Analyze
            foreach ($this->systems as $system) {
                $system->provide($record);
            }

            $this->stack->pop();
        }
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
}
