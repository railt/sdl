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
use Railt\SDL\Frontend\IR\Collection;
use Railt\SDL\Frontend\IR\Deferred;
use Railt\SDL\Frontend\IR\Opcode\OpenOpcode;
use Railt\SDL\Frontend\IR\OpcodeInterface;
use Railt\SDL\Frontend\IR\Prototype;

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
     * Frontend constructor.
     */
    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * @param Readable $file
     * @return iterable|OpcodeInterface[]
     * @throws SyntaxException
     */
    public function load(Readable $file): iterable
    {
        $context = new Context();

        return $this->collect($file, $context);
    }

    /**
     * @param Readable $file
     * @param Context $context
     * @return Collection|OpcodeInterface[]
     * @throws SyntaxException
     */
    private function collect(Readable $file, Context $context): Collection
    {
        // Create a container in which all the opcodes are stored.
        $collection = new Collection($context);

        $collection->add(new OpenOpcode($file), $file);

        // We start bypassing and add each element to the collection.
        $iterator = $this->bypass($this->parse($file), $context);

        while ($iterator->valid()) {
            [$ast, $result] = [$iterator->key(), $iterator->current()];

            // If an unmounted opcode (prototype) is returned,
            // then attach it and return it back.
            if ($result instanceof OpcodeInterface) {
                $result = $collection->add($result, $file, $ast->getOffset());
            }

            // If the result is callable/invocable, then we create
            // a pending (deferred) execution element.
            if (\is_callable($result)) {
                $result = new Deferred($ast, $result);
            }

            $iterator->send($result);

            if ($this->logger) {
                $this->log($result);
            }
        }

        return $collection;
    }

    /**
     * @param mixed $value
     * @return void
     */
    private function log($value): void
    {
        if ($value instanceof OpcodeInterface) {
            $this->logger->debug((string)$value);
        } else {
            $this->logger->info(\gettype($value));
        }
    }

    /**
     * A method for recursively traversing all rules of an Abstract
     * Syntax Tree to obtain all the opcodes that the tree provides.
     *
     * @param NodeInterface|RuleInterface $node
     * @param Context $context
     * @return iterable|OpcodeInterface[]|\Generator
     */
    private function bypass(NodeInterface $node, Context $context): \Generator
    {
        foreach ($node->getChildren() as $child) {
            /** @var Deferred[] $deferred */
            $deferred = [];

            $current = $context->current();

            // Is AST Rule provides opcodes list?
            if ($child instanceof ProvidesOpcode) {
                $iterator = $this->extract($child, $child->getOpcodes($context));

                yield from \iterator_reverse_each($iterator, function ($response) use (&$deferred): void {
                    // In the event that the parent sends a deferred callback
                    // back, we memorize the reference to him in order
                    // to fulfill in the future.
                    if ($response instanceof Deferred) {
                        $deferred[] = $response;
                    }
                });
            }

            // Is the AST provides other children?
            if ($child instanceof RuleInterface) {
                yield from $this->bypass($child, $context);
            }

            // Execute pending elements before closing the context.
            foreach ($deferred as $callable) {
                yield from $this->extract($child, $callable->resolve());
            }

            // Is the context was changed at runtime - close
            // it and restore the previous one.
            if ($current !== $context->current()) {
                $context->close();
            }
        }
    }

    /**
     * Method for unpacking the list of opcodes from the rule.
     *
     * @param RuleInterface $key
     * @param \Generator $iterator
     * @return \Generator|OpcodeInterface[]
     */
    private function extract(RuleInterface $key, \Generator $iterator): \Generator
    {
        while ($iterator->valid()) {
            // Take an AST node
            $node = $this->extractKey($iterator, $key);

            // We return the prototype and get the opcode:
            // ie with reference to the file, line, column, offset, etc.,
            // including the identifier of the opcode inside the collection.
            $opcode = yield $node => $iterator->current();

            // Transfer control back to the AST.
            $iterator->send($opcode);
        }
    }

    /**
     * Make sure that the key is a valid AST rule that contains
     * a link to the position inside the file.
     *
     * @param \Generator $current
     * @param RuleInterface $parent
     * @return RuleInterface
     */
    private function extractKey(\Generator $current, RuleInterface $parent): RuleInterface
    {
        $key = $current->key();

        return $key instanceof RuleInterface ? $key : $parent;
    }

    /**
     * Parse the file using top-down parser and
     * return the Abstract Syntax Tree.
     *
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

    /**
     * @param LoggerInterface $logger
     * @return Frontend
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }
}
