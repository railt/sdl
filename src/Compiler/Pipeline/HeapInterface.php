<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

/**
 * Interface HeapInterface
 */
interface HeapInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param PriorityInterface $priority
     */
    public function push(PriorityInterface $priority): void;

    /**
     * @return PriorityInterface
     */
    public function pop(): PriorityInterface;
}
