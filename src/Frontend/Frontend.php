<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Railt\Io\Readable;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\Exception\UnrecognizedTokenException;
use Railt\SDL\Exception\SyntaxException;
use Railt\SDL\Frontend\AST\ProvidesOpcode;
use Railt\SDL\Frontend\IR\Opcode;
use Railt\SDL\Frontend\IR\Opcode\OpenOpcode;
use Railt\SDL\Frontend\IR\OpcodeHeap;
use Railt\SDL\Frontend\IR\OpcodeInterface;

/**
 * Class Frontend
 */
class Frontend implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Analyzer
     */
    private $analyzer;

    /**
     * Frontend constructor.
     */
    public function __construct()
    {
        $this->parser  = new Parser();
        $this->analyzer = new Analyzer();
    }

    /**
     * @param Readable $file
     * @return iterable|OpcodeInterface[]
     * @throws SyntaxException
     */
    public function load(Readable $file): iterable
    {
        $context = new Context();

        $opcodes = $this->collect($file, $context);
        $opcodes = $this->analyzer->analyze($opcodes);

        return $opcodes;
    }

    /**
     * @param LoggerInterface $logger
     * @return Frontend
     */
    public function setLogger(LoggerInterface $logger): Frontend
    {
        $this->logger = $logger;
        $this->analyzer->setLogger($logger);

        return $this;
    }

    /**
     * @param Readable $file
     * @param Context $context
     * @return iterable|OpcodeInterface[]
     * @throws SyntaxException
     */
    private function collect(Readable $file, Context $context): iterable
    {
        $heap = new OpcodeHeap($context);

        $heap->add(new OpenOpcode($file), $file);

        $iterator = $this->bypass($this->parse($file), $context);

        while ($iterator->valid()) {
            [$ast, $opcode] = [$iterator->key(), $iterator->current()];

            $iterator->send($heap->add($opcode, $file, $ast->getOffset()));
        }

        return $heap;
    }

    /**
     * @param NodeInterface $node
     * @param Context $context
     * @return iterable|Opcode[]|\Generator
     */
    private function bypass(NodeInterface $node, Context $context): \Generator
    {
        foreach ($node as $child) {
            $current = $context->current();

            if ($child instanceof ProvidesOpcode) {
                yield from $this->extract($child, $context);
            }

            if ($child instanceof RuleInterface) {
                yield from $this->bypass($child, $context);
            }

            if ($current !== $context->current()) {
                $context->close();
            }
        }
    }

    /**
     * @param ProvidesOpcode $provider
     * @param Context $context
     * @return \Generator|OpcodeInterface[]
     */
    private function extract(ProvidesOpcode $provider, Context $context): \Generator
    {
        /** @var \Generator $iterator */
        $iterator = $provider->getOpcodes($context);

        while ($iterator->valid()) {
            $key = $iterator->key();

            $iterator->send(yield $key instanceof RuleInterface ? $key : $provider => $iterator->current());
        }
    }

    /**
     * @param Readable $file
     * @return RuleInterface
     * @throws SyntaxException
     */
    private function parse(Readable $file): RuleInterface
    {
        try {
            return $this->parser->parse($file);
        } catch (UnexpectedTokenException | UnrecognizedTokenException $e) {
            $error = new SyntaxException($e->getMessage(), $e->getCode());
            $error->throwsIn($file, $e->getLine(), $e->getColumn());

            throw $error;
        }
    }
}
