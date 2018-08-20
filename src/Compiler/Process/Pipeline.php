<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Process;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class Pipeline
 */
class Pipeline implements \IteratorAggregate, \Countable, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Example struct:
     *
     * <code>
     *  [
     *      PRIORITY_1 => \SplQueue([1, 2, 3]),
     *      PRIORITY_2 => \SplQueue([1, 2, 3]),
     *      PRIORITY_3 => \SplQueue([1, 2, 3]),
     *  ]
     * </code>
     *
     * @var \SplQueue[]
     */
    private $queue = [];

    /**
     * @return \Traversable|Emittable[]
     */
    public function getIterator(): \Traversable
    {
        while ($next = $this->next()) {
            yield $next;
        }
    }

    /**
     * @return null|DeferredInterface
     */
    private function next(): ?DeferredInterface
    {
        foreach ($this->queue as $queue) {
            if ($queue->count() > 0) {
                return $queue->shift();
            }
        }

        return null;
    }

    /**
     * @param int $priority
     * @return DeferredInterface|Emittable
     */
    public function on(int $priority): DeferredInterface
    {
        return $this->push($priority, new Deferred());
    }

    /**
     * @param DeferredInterface $handler
     */
    private function log(DeferredInterface $handler): void
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS)[3];

        $this->logger->debug(\sprintf('Add deferred execution (%s:%d)', $trace['file'], $trace['line']));

        $handler->then(function() use ($trace) {
            $this->logger->debug(\sprintf('Execute deferred handler (%s:%d)', $trace['file'], $trace['line']));
        });

        $handler->catch(function(\Throwable $e) use ($trace) {
            $this->logger->debug(\sprintf('Deferred handler rejection (%s:%d)', $trace['file'], $trace['line']));
            $this->logger->error($e);

            throw $e;
        });
    }

    /**
     * @param int $priority
     * @param Emittable $handler
     * @return Emittable
     */
    public function push(int $priority, Emittable $handler): Emittable
    {
        if ($this->logger && $handler instanceof DeferredInterface) {
            $this->log($handler);
        }

        if (! \array_key_exists($priority, $this->queue)) {
            $this->queue[$priority] = $this->createCollection();
        }

        $this->queue[$priority]->push($handler);

        return $handler;
    }

    /**
     * @return \SplDoublyLinkedList
     */
    protected function createCollection(): \SplDoublyLinkedList
    {
        return new \SplQueue();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $accumulator = function (int $result, \SplQueue $queue): int {
            return $result + $queue->count();
        };

        return (int)\array_reduce($this->queue, $accumulator, 0);
    }
}
