<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Compiler\ParserInterface;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Component\TypeName;
use Railt\SDL\Compiler\Context\GlobalContext;
use Railt\SDL\Compiler\Context\GlobalContextInterface;
use Railt\SDL\Compiler\Context\LocalContextInterface;
use Railt\SDL\Compiler\Record\InterfaceDefinitionRecord;
use Railt\SDL\Compiler\Record\ObjectDefinitionRecord;
use Railt\SDL\Compiler\Record\RecordInterface;
use Railt\SDL\ECS\Container;
use Railt\SDL\ECS\EntityInterface;
use Railt\SDL\ECS\SystemInterface;
use Railt\SDL\Exception\BadAstMappingException;
use Railt\SDL\Heap\HeapInterface;
use Railt\SDL\Heap\StackHeap;
use Railt\SDL\Stack\CallStack;

/**
 * Class Pipeline
 */
class Pipeline implements PipelineInterface
{
    /**
     * @var SystemInterface[]|string[]
     */
    private const SYSTEMS = [
        System\DeclarationSystem::class,
        System\DependenciesResolver::class,
    ];

    /**
     * @var RecordInterface[]|EntityInterface[]|string[]
     */
    private const DEFINITIONS = [
        '#ObjectDefinition'    => ObjectDefinitionRecord::class,
        '#InterfaceDefinition' => InterfaceDefinitionRecord::class,
    ];

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var CallStack
     */
    private $stack;

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
        $this->parser  = Parser::new();
        $this->context = new GlobalContext($stack);
    }

    /**
     * @param Readable $file
     * @return HeapInterface
     */
    public function parse(Readable $file): HeapInterface
    {
        $heap    = new StackHeap();
        $systems = $this->getSystems($heap);

        $resolver = function (LocalContextInterface $context) use ($heap, $systems): void {
            /** @var RuleInterface $ast */
            $ast = $this->parser->parse($context->getFile());

            foreach ($ast->getChildren() as $child) {
                $this->stack->pushAst($context->getFile(), $child);

                /** @var $record EntityInterface|RecordInterface */
                $heap->push($record = $this->resolve($child, $context->current()));

                $systems->resolve($record);

                $this->stack->pop();
            }
        };

        $this->context->transact(TypeName::anonymous($this->context), $file, $resolver);

        return $heap;
    }

    /**
     * @param HeapInterface $heap
     * @return Container
     */
    private function getSystems(HeapInterface $heap): Container
    {
        $instance = new Container(function (string $system) use ($heap): SystemInterface {
            return new $system($this->stack, $heap);
        });

        foreach (self::SYSTEMS as $system) {
            $instance->addSystem($system);
        }

        return $instance;
    }

    /**
     * @param RuleInterface $ast
     * @param LocalContextInterface $context
     * @return RecordInterface
     * @throws BadAstMappingException
     */
    private function resolve(RuleInterface $ast, LocalContextInterface $context): RecordInterface
    {
        $key = $ast->getName();

        if (\array_key_exists($key, self::DEFINITIONS)) {
            $record = self::DEFINITIONS[$key];

            return new $record($context, $ast);
        }

        throw new BadAstMappingException('Unprocessable production ' . $key, $this->stack);
    }
}
