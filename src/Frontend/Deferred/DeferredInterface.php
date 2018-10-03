<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Deferred;

use Railt\SDL\Frontend\Context\ContextInterface;

/**
 * Interface DeferredInterface
 */
interface DeferredInterface
{
    /**
     * @param \Closure $then
     * @return DeferredInterface
     */
    public function then(\Closure $then): DeferredInterface;

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @param int $offset
     * @return DeferredInterface
     */
    public function definedIn(int $offset): DeferredInterface;

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface;

    /**
     * @param mixed ...$args
     * @return \Generator
     */
    public function invoke(...$args): \Generator;
}
