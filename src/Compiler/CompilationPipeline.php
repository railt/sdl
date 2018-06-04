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
use Railt\SDL\Compiler\Context\GlobalContext;
use Railt\SDL\Compiler\Context\GlobalContextInterface;
use Railt\SDL\Compiler\Context\LocalContextInterface;
use Railt\SDL\Compiler\Record\ObjectDefinitionRecord;
use Railt\SDL\Compiler\Record\RecordInterface;
use Railt\SDL\Exception\BadAstMappingException;
use Railt\SDL\Heap\HeapInterface;
use Railt\SDL\Heap\StackHeap;
use Railt\SDL\Stack\CallStack;

/**
 * Class Pipeline
 */
class CompilationPipeline implements PipelineInterface
{
    /**
     * @var int[]
     */
    private const DEFINITIONS = [
        '#ObjectDefinition' => ObjectDefinitionRecord::class,
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
        $this->stack    = $stack;
        $this->parser   = Parser::new();
        $this->context  = new GlobalContext($stack);
    }

    /**
     * @param Readable $file
     * @return HeapInterface
     */
    public function parse(Readable $file): HeapInterface
    {
        $heap = new StackHeap();

        $this->context->transact($file, function(LocalContextInterface $context) use ($heap) {

            /** @var RuleInterface $ast */
            $ast = $this->parser->parse($context->getFile());

            $this->stack->pushAst($context->getFile(), $ast);

            foreach ($ast->getChildren() as $child) {
                $this->stack->pushAst($context->getFile(), $child);
                $heap->push($this->resolve($child, $context->current()));
                $this->stack->pop();
            }

            $this->stack->pop();
        });

        return $heap;
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
