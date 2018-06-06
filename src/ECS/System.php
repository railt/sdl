<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\ECS;

use Railt\SDL\Heap\HeapInterface;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class System
 */
abstract class System implements SystemInterface
{
    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * @var HeapInterface
     */
    private $heap;

    /**
     * System constructor.
     * @param CallStackInterface $stack
     * @param HeapInterface $heap
     */
    public function __construct(CallStackInterface $stack, HeapInterface $heap)
    {
        $this->stack = $stack;
        $this->heap  = $heap;
    }

    /**
     * @return HeapInterface
     */
    public function getHeap(): HeapInterface
    {
        return $this->heap;
    }

    /**
     * @return CallStackInterface
     */
    public function getStack(): CallStackInterface
    {
        return $this->stack;
    }

    /**
     * @param EntityInterface $entity
     * @return EntityResolver
     */
    protected function entity(EntityInterface $entity): EntityResolver
    {
        return new EntityResolver($entity);
    }
}
