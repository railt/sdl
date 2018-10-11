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
 * Class Process
 */
class Process implements ProcessInterface
{
    /**
     * @var array|InterceptorInterface[]
     */
    private $interceptors = [];

    /**
     * @var array|callable[]
     */
    private $ticks = [];

    /**
     * @var \SplQueue|DeferredInterface[]
     */
    private $deferred;

    /**
     * Process constructor.
     */
    public function __construct()
    {
        $this->deferred = new \SplQueue();
    }

    /**
     * @param mixed $process
     * @return mixed
     */
    public function await($process)
    {
        return $this->runDeferredResolvers($this->exec($process));
    }

    /**
     * @param mixed $process
     * @return mixed
     */
    private function exec($process)
    {
        return $process instanceof \Generator ? $this->coroutine($process) : $this->each($process);
    }

    /**
     * @param callable $callable
     * @return DeferredInterface
     */
    public function deferred(callable $callable): DeferredInterface
    {
        return $this->deferred[] = new Deferred($this, $callable);
    }

    /**
     * @return iterable|DeferredInterface[]
     */
    private function getDeferredResolvers(): iterable
    {
        while ($this->deferred->count()) {
            yield $this->deferred->pop();
        }
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function runDeferredResolvers($value)
    {
        foreach ($this->getDeferredResolvers() as $resolver) {
            $this->each($resolver->invoke($value));
        }

        return $value;
    }

    /**
     * @param \Generator $process
     * @return mixed
     */
    private function coroutine(\Generator $process)
    {
        while ($process->valid()) {
            $process->send($this->each($process->current()));
        }

        return $this->each($process->getReturn());
    }

    /**
     * @param callable $handler
     * @return ProcessInterface
     */
    public function tick(callable $handler): ProcessInterface
    {
        $this->ticks[] = $handler;

        return $this;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function each($value)
    {
        return $this->runTickHandlers($this->reduce($this->interceptors, $value));
    }

    /**
     * @param array $interceptors
     * @param mixed $value
     * @return mixed
     */
    private function reduce(array $interceptors, $value)
    {
        foreach ($interceptors as $index => $interceptor) {
            if ($interceptor->match($value)) {
                $value = $interceptor->invoke($value);

                return $this->reduce($this->arrayExcept($interceptors, $index), $value);
            }
        }

        return $value;
    }

    /**
     * @param array $items
     * @param int $index
     * @return array
     */
    private function arrayExcept(array $items, int $index): array
    {
        unset($items[$index]);

        return $items;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function runTickHandlers($value)
    {
        foreach ($this->ticks as $tick) {
            $tick($value);
        }

        return $value;
    }

    /**
     * @param InterceptorInterface $interceptor
     * @return ProcessInterface
     */
    public function intercept(InterceptorInterface $interceptor): ProcessInterface
    {
        $this->interceptors[] = $interceptor;

        return $this;
    }

    /**
     * @param mixed|\Generator $result
     * @return \Generator
     */
    public static function toGenerator($result): \Generator
    {
        if ($result instanceof \Generator) {
            yield from $result;

            return $result->getReturn();
        }

        return $result;
    }
}
