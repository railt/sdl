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
use Railt\SDL\Compiler\Context\Pool;
use Railt\SDL\Compiler\Record\DefinitionRecord;
use Railt\SDL\Compiler\Record\ExtensionRecord;
use Railt\SDL\Compiler\Record\InvocationRecord;
use Railt\SDL\Compiler\Record\RecordInterface;
use Railt\SDL\Exception\BadAstMappingException;
use Railt\SDL\Parser\Factory;
use Railt\SDL\Stack\CallStack;

/**
 * Class HeadingsTable
 */
class HeadingsTable implements PrebuiltTypes
{
    /**
     * @var int[]
     */
    private const DEFINITIONS = [
        '#DirectiveDefinition' => DefinitionRecord::class,
        '#EnumDefinition'      => DefinitionRecord::class,
        '#InputDefinition'     => DefinitionRecord::class,
        '#InterfaceDefinition' => DefinitionRecord::class,
        '#NamespaceDefinition' => DefinitionRecord::class,
        //'#ObjectDefinition'    => DefinitionRecord::class,
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
     * @var ProvidesTypes
     */
    private $types;

    /**
     * @var Linker
     */
    private $linker;

    /**
     * HeadingsTable constructor.
     * @param CallStack $stack
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __construct(CallStack $stack)
    {
        $this->stack  = $stack;
        $this->parser = Factory::create();
        $this->types  = new Container($stack);
        $this->linker = new Linker($this->types, $this);
    }

    /**
     * @param Readable $file
     * @return ProvidesTypes
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    public function extract(Readable $file): ProvidesTypes
    {
        $context = new Pool($file, $this->stack);

        $ast = $this->parse($file);

        foreach ($ast->getChildren() as $rule) {
            $record = $this->astToRecord($file, $rule, $context->current());
        }

        return $this->types;
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
     * @param RuleInterface $rule
     * @param ContextInterface $context
     * @return RecordInterface
     * @throws BadAstMappingException
     * @throws \Railt\SDL\Exception\LossOfStackException
     */
    private function astToRecord(Readable $file, RuleInterface $rule, ContextInterface $context): RecordInterface
    {
        $this->stack->pushAst($file, $rule);
        $record = $this->getRecord($rule, $context);
        $this->stack->pop();

        return $record;
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
}
