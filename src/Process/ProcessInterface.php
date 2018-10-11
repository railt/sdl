<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Process;

/**
 * Interface ProcessInterface
 */
interface ProcessInterface
{
    /**
     * @param mixed $process
     * @return mixed
     */
    public function await($process);

    /**
     * @param callable $handler
     * @return ProcessInterface
     */
    public function tick(callable $handler): self;

    /**
     * @param callable $callable
     * @return DeferredInterface
     */
    public function deferred(callable $callable): DeferredInterface;

    /**
     * @param InterceptorInterface $interceptor
     * @return ProcessInterface
     */
    public function intercept(InterceptorInterface $interceptor): self;
}
