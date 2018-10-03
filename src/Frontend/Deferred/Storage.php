<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Deferred;

use Railt\SDL\Frontend\Map;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class Storage
 */
class Storage implements \IteratorAggregate, \Countable
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var Map<TypeNameInterface|int,DeferredInterface>
     */
    private $deferred;

    /**
     * Storage constructor.
     */
    public function __construct()
    {
        $this->deferred = new Map();
    }

    /**
     * @param DeferredInterface $deferred
     * @return DeferredInterface
     */
    public function add(DeferredInterface $deferred): DeferredInterface
    {
        $id = $deferred instanceof Identifiable ? $deferred->getDefinition()->getName() : $this->id++;

        return $this->deferred[$id] = $deferred;
    }

    /**
     * @param TypeNameInterface $name
     * @return null|DeferredInterface
     */
    public function first(TypeNameInterface $name): ?DeferredInterface
    {
        return $this->deferred[$name] ?? null;
    }

    /**
     * @param \Closure $filter
     * @return iterable
     */
    public function only(\Closure $filter): iterable
    {
        foreach ($this->getIterator() as $key => $deferred) {
            if ($filter($deferred, $key)) {
                yield $key => $deferred;
            }
        }
    }

    /**
     * @param \Closure $filter
     * @return iterable|DeferredInterface[]
     */
    public function extract(\Closure $filter): iterable
    {
        foreach ($this->only($filter) as $key => $deferred) {
            if ($filter($deferred, $key)) {
                $this->deferred->delete($key);

                yield $key => $deferred;
            }
        }
    }

    /**
     * @return iterable|DeferredInterface[]
     */
    public function getIterator(): iterable
    {
        foreach ($this->deferred as $key => $deferred) {
            yield $key => $deferred;
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->deferred);
    }
}
