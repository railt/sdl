<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Deferred;

/**
 * Class Storage
 */
class Storage implements \IteratorAggregate, \Countable
{
    /**
     * @var array|DeferredInterface[]
     */
    private $queue;

    /**
     * Storage constructor.
     */
    public function __construct()
    {
        $this->queue = new \SplQueue();
    }

    /**
     * @param DeferredInterface $deferred
     * @return DeferredInterface
     */
    public function add(DeferredInterface $deferred): DeferredInterface
    {
        $this->queue->push($deferred);

        return $deferred;
    }

    /**
     * @param iterable|DeferredInterface[] $deferred
     */
    public function attach(iterable $deferred): void
    {
        foreach ($deferred as $callback) {
            $this->add($callback);
        }
    }

    /**
     * @return iterable|\Generator
     */
    public function getIterator(): iterable
    {
        while ($this->queue->count()) {
            $deferred = $this->queue->shift();

            yield from $this->toIterator($deferred());
        }
    }

    /**
     * @param mixed $result
     * @return \Generator
     */
    private function toIterator($result): \Generator
    {
        if ($result instanceof \Generator) {
            yield from $result;
            yield $result->getReturn();
        } else {
            yield $result;
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->queue->count();
    }
}
