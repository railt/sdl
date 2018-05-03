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
use Railt\SDL\Compiler\Identifier\DirectiveExtractor;
use Railt\SDL\Parser\Factory;
use Railt\SDL\Stack\CallStack;

/**
 * Class Context
 */
class Linker
{
    private const PRIORITY_DEFINITION = 3;
    private const PRIORITY_EXTENSION = 2;
    private const PRIORITY_INVOCATION = 1;
    private const PRIORITY_UNEXPECTED = 0;

    /**
     * AST Mappings
     */
    private const PRIORITIES = [
        '#DirectiveDefinition' => self::PRIORITY_DEFINITION,
        '#EnumDefinition'      => self::PRIORITY_DEFINITION,
        '#InputDefinition'     => self::PRIORITY_DEFINITION,
        '#InterfaceDefinition' => self::PRIORITY_DEFINITION,
        '#ObjectDefinition'    => self::PRIORITY_DEFINITION,
        '#ScalarDefinition'    => self::PRIORITY_DEFINITION,
        '#SchemaDefinition'    => self::PRIORITY_DEFINITION,
        '#UnionDefinition'     => self::PRIORITY_DEFINITION,

        '#EnumExtension'      => self::PRIORITY_EXTENSION,
        '#InputExtension'     => self::PRIORITY_EXTENSION,
        '#InterfaceExtension' => self::PRIORITY_EXTENSION,
        '#ObjectExtension'    => self::PRIORITY_EXTENSION,
        '#ScalarExtension'    => self::PRIORITY_EXTENSION,
        '#SchemaExtension'    => self::PRIORITY_EXTENSION,
        '#UnionExtension'     => self::PRIORITY_EXTENSION,

        '#Directive' => self::PRIORITY_INVOCATION,
    ];

    /**
     * AST Identifier extractors
     */
    private const IDENTIFIERS = [
        '#DirectiveDefinition' => DirectiveExtractor::class,
        '#EnumDefinition'      => DirectiveExtractor::class,
        '#InputDefinition'     => DirectiveExtractor::class,
        '#InterfaceDefinition' => DirectiveExtractor::class,
        '#ObjectDefinition'    => DirectiveExtractor::class,
        '#ScalarDefinition'    => DirectiveExtractor::class,
        '#SchemaDefinition'    => DirectiveExtractor::class,
        '#UnionDefinition'     => DirectiveExtractor::class,
    ];

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var Factory
     */
    private $parser;

    /**
     * @var \SplPriorityQueue
     */
    private $queue;

    /**
     * @var array
     */
    private $identifiers = [];

    /**
     * Context constructor.
     * @param TypeLoader $loader
     * @param CallStack $stack
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __construct(TypeLoader $loader, CallStack $stack)
    {
        $this->stack  = $stack;
        $this->parser = Factory::create();
        $this->queue  = new \SplPriorityQueue();
    }

    /**
     * @param Readable $file
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    public function process(Readable $file)
    {
        $this->extract($this->parse($file));


    }

    /**
     * @param RuleInterface $ast
     */
    private function extract(RuleInterface $ast): void
    {
        foreach ($ast->getChildren() as $rule) {
            $name = $rule->getName();

            $priority = self::PRIORITIES[$name] ?? self::PRIORITY_UNEXPECTED;

            $this->register($rule);

            $this->queue->insert($rule, $priority);
        }
    }

    /**
     * @param RuleInterface $ast
     */
    private function register(RuleInterface $ast): void
    {
        if (! \in_array($ast->getName(), $this->identifiers, true)) {
            $this->identifiers[] = $ast->getName();
        }
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
}
