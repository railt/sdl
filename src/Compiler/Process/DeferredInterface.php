<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Process;

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
     * @param \Closure $exception
     * @return DeferredInterface
     */
    public function catch(\Closure $exception): DeferredInterface;
}
