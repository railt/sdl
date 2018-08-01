<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

/**
 * Class Pipeline
 */
class Pipeline implements \IteratorAggregate, \Countable
{
    /**
     * @var int
     */
    public const PRIORITY_DEFINITION = 0x01;

    /**
     * @var int
     */
    public const PRIORITY_EXTENSION = 0x02;

    /**
     * @var int
     */
    public const PRIORITY_INVOCATION = 0x03;

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
     * @return \Traversable|callable[]
     */
    public function getIterator(): \Traversable
    {
        while ($next = $this->next()) {
            yield $next;
        }
    }

    /**
     * @return null|callable
     */
    private function next(): ?callable
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
     * @param callable $then
     */
    public function push(int $priority, callable $then): void
    {
        if (! \array_key_exists($priority, $this->queue)) {
            $this->queue[$priority] = $this->createList();
        }

        $this->queue[$priority]->push($then);
    }

    /**
     * @return \SplDoublyLinkedList
     */
    protected function createList(): \SplDoublyLinkedList
    {
        return new \SplQueue();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return (int)\array_reduce($this->queue, function (int $result, \SplQueue $queue): int {
            return $result + $queue->count();
        }, 0);
    }

    /**
     * @return callable
     * @throws \UnderflowException
     */
    public function pop(): callable
    {
        $result = $this->next();

        if ($result === null) {
            throw new \UnderflowException('Can not fetch data from empty heap');
        }

        return $result;
    }
}
