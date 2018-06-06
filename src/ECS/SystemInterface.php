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
 * Interface SystemInterface
 */
interface SystemInterface
{
    /**
     * @return HeapInterface
     */
    public function getHeap(): HeapInterface;

    /**
     * @return CallStackInterface
     */
    public function getStack(): CallStackInterface;

    /**
     * @param EntityInterface $entity
     */
    public function resolve(EntityInterface $entity): void;
}
