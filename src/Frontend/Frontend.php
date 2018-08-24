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
     * @var OpcodeHeap
     */
    private $heap;

    /**
     * @var Context
     */
    private $context;

    /**
     * Frontend constructor.
     */
    public function __construct()
    {
        $this->parser = new Parser();
        $this->context = new Context();
        $this->heap = new OpcodeHeap($this->context);
    }

    /**
     * @param Readable $file
     * @return iterable|OpcodeInterface[]
     * @throws SyntaxException
     */
    public function load(Readable $file): iterable
    {
        $this->context->create();

        $this->heap->add(new OpenOpcode($file), $file);

        $iterator = $this->bypass($this->parse($file));

        while ($iterator->valid()) {
            [$ast, $opcode] = [$iterator->key(), $iterator->current()];

            $iterator->send($this->heap->add($opcode, $file, $ast->getOffset()));
        }

        foreach ($this->heap as $opcode) {
            yield $opcode;

            if ($this->logger) {
                $this->logger->debug($opcode);
            }
        }
    }

    /**
     * @param NodeInterface $node
     * @return iterable|Opcode[]|\Generator
     */
    private function bypass(NodeInterface $node): \Generator
    {
        foreach ($node as $child) {
            $current = $this->context->current();

            if ($child instanceof ProvidesOpcode) {
                yield from $this->extract($child);
            }

            if ($child instanceof RuleInterface) {
                yield from $this->bypass($child);
            }

            if ($current !== $this->context->current()) {
                $this->context->close();
            }
        }
    }

    /**
     * @param ProvidesOpcode $provider
     * @return \Generator|OpcodeInterface[]
     */
    private function extract(ProvidesOpcode $provider): \Generator
    {
        /** @var \Generator $iterator */
        $iterator = $provider->getOpcodes($this->context);

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
