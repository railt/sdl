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
 * Class Deferred
 */
class Deferred implements DeferredInterface, Emittable
{
    /**
     * @var \SplQueue|\Closure[]
     */
    private $handlers;

    /**
     * @var \SplQueue|\Closure[]
     */
    private $interceptors;

    /**
     * Deferred constructor.
     */
    public function __construct()
    {
        $this->handlers = new \SplQueue();
        $this->interceptors = new \SplQueue();
    }

    /**
     * @param \Closure $then
     * @return DeferredInterface
     */
    public function then(\Closure $then): DeferredInterface
    {
        $this->handlers->push($then);

        return $this;
    }

    /**
     * @param \Closure $exception
     * @return DeferredInterface
     */
    public function catch(\Closure $exception): DeferredInterface
    {
        $this->interceptors->push($exception);

        return $this;
    }

    /**
     * @return \Generator
     * @throws \Throwable
     */
    public function emit(): \Generator
    {
        while ($this->handlers->count() > 0) {
            $callback = $this->handlers->shift();

            try {
                yield $callback();
            } catch (\Throwable $e) {
                $this->throw($e);
            }
        }
    }

    /**
     * @return mixed|null
     * @throws \Throwable
     */
    public function wait()
    {
        foreach ($ctx = $this->emit() as $i => $tick) {
            if ($tick instanceof \Throwable) {
                $ctx->throw($tick);
            }
        }

        return $ctx->getReturn();
    }

    /**
     * @param \Throwable $exception
     * @throws \Throwable
     */
    private function throw(\Throwable $exception)
    {
        if ($this->interceptors->count() === 0) {
            throw $exception;
        }

        while ($this->interceptors->count() > 0) {
            $catch = $this->interceptors->shift();

            try {
                $catch($exception);
            } catch (\Throwable $e) {
                $this->throw($e);
            }
        }
    }
}
