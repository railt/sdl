<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Class StackHeap
 */
class StackHeap implements HeapInterface
{
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
     * @return \Traversable|RecordInterface[]
     */
    public function getIterator(): \Traversable
    {
        while ($next = $this->next()) {
            yield $next;
        }
    }

    /**
     * @return null|PriorityInterface
     */
    private function next(): ?PriorityInterface
    {
        foreach ($this->queue as $queue) {
            if ($queue->count() > 0) {
                return $queue->shift();
            }
        }

        return null;
    }

    /**
     * @param PriorityInterface $value
     */
    public function push(PriorityInterface $value): void
    {
        if (! \array_key_exists($value->getPriority(), $this->queue)) {
            $this->queue[$value->getPriority()] = new \SplQueue();
        }

        $this->queue[$value->getPriority()]->push($value);
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

    public function pop(): PriorityInterface
    {
        throw new \LogicException('The ' . __METHOD__ . ' not implemented yet');
    }
}
