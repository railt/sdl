<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System\Support;

use Railt\SDL\Compiler\Process\DeferredInterface;
use Railt\SDL\Compiler\System\SystemInterface;

/**
 * Trait DeferredPriorities
 * @mixin SystemInterface
 */
trait DeferredPriorities
{
    /**
     * @param int $priority
     * @return DeferredInterface
     */
    abstract protected function when(int $priority): DeferredInterface;

    /**
     * @param \Closure $then
     * @return DeferredInterface
     */
    protected function deferred(\Closure $then): DeferredInterface
    {
        return $this->when(SystemInterface::PRIORITY_DEFERRED)->then($then);
    }

    /**
     * @param \Closure $then
     * @return DeferredInterface
     */
    protected function linker(\Closure $then): DeferredInterface
    {
        return $this->when(SystemInterface::PRIORITY_LINKING)->then($then);
    }

    /**
     * @param \Closure $then
     * @return DeferredInterface
     */
    protected function extend(\Closure $then): DeferredInterface
    {
        return $this->when(SystemInterface::PRIORITY_EXTENSION)->then($then);
    }

    /**
     * @param \Closure $then
     * @return DeferredInterface
     */
    protected function inference(\Closure $then): DeferredInterface
    {
        return $this->when(SystemInterface::PRIORITY_INFERENCE)->then($then);
    }

    /**
     * @param \Closure $then
     * @return DeferredInterface
     */
    protected function runtime(\Closure $then): DeferredInterface
    {
        return $this->when(SystemInterface::PRIORITY_RUNTIME)->then($then);
    }

    /**
     * @param \Closure $then
     * @return DeferredInterface
     */
    protected function complete(\Closure $then): DeferredInterface
    {
        return $this->when(SystemInterface::PRIORITY_COMPLETE)->then($then);
    }
}
