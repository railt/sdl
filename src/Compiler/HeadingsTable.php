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
use Railt\SDL\Compiler\Record\DefinitionRecord;
use Railt\SDL\Compiler\Record\ExtensionRecord;
use Railt\SDL\Compiler\Record\InvocationRecord;
use Railt\SDL\Compiler\Record\NamespaceDefinitionRecord;
use Railt\SDL\Compiler\Record\ObjectDefinitionRecord;
use Railt\SDL\Compiler\Record\RecordInterface;
use Railt\SDL\Compiler\Record\SchemaDefinitionRecord;
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
        '#NamespaceDefinition' => NamespaceDefinitionRecord::class,
        '#ObjectDefinition'    => ObjectDefinitionRecord::class,
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
     * @var TypeLoader
     */
    private $loader;

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
        $this->loader = new TypeLoader($this->types, $this);
    }

    /**
     * @param Readable $file
     * @return ProvidesTypes
     * @throws BadAstMappingException
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \Railt\SDL\Exception\LossOfStackException
     * @throws \RuntimeException
     */
    public function extract(Readable $file): ProvidesTypes
    {
        $ast = $this->parse($file);

        foreach ($ast->getChildren() as $rule) {
            // Convert AST NodeInterface to RecordInterface
            $record = $this->astToRecord($file, $rule);

            $this->registerRecord($record);
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
     * @return RecordInterface
     * @throws BadAstMappingException
     * @throws \Railt\SDL\Exception\LossOfStackException
     */
    private function astToRecord(Readable $file, RuleInterface $rule): RecordInterface
    {
        $this->stack->pushAst($file, $rule);
        $record = $this->getRecord($file, $rule);
        $this->stack->pop();

        return $record;
    }

    /**
     * @param Readable $file
     * @param RuleInterface $rule
     * @return RecordInterface
     * @throws BadAstMappingException
     */
    private function getRecord(Readable $file, RuleInterface $rule): RecordInterface
    {
        $class = self::DEFINITIONS[$rule->getName()] ?? null;

        if ($class) {
            return new $class($file, $rule, $this->stack);
        }

        $error = \sprintf('Undefined AST node name %s', $rule->getName());
        throw new BadAstMappingException($error, $this->stack);
    }

    /**
     * @param RecordInterface $record
     */
    private function registerRecord(RecordInterface $record): void
    {
        // TODO Record stack push

        $this->types->push($record);

        // TODO Record stack pop
    }
}
