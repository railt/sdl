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
 * Class Deferred
 */
class Deferred implements DeferredInterface
{
    /**
     * @var ProcessInterface
     */
    private $process;

    /**
     * @var \Closure[]
     */
    private $then = [];

    /**
     * Deferred constructor.
     * @param ProcessInterface $process
     * @param \Closure $then
     */
    public function __construct(ProcessInterface $process, \Closure $then)
    {
        $this->process = $process;
        $this->then($then);
    }

    /**
     * @param callable $then
     * @return DeferredInterface
     */
    public function then(callable $then): DeferredInterface
    {
        $this->then[] = $then;

        return $this;
    }

    /**
     * @param mixed ...$args
     * @return mixed
     */
    public function __invoke(...$args)
    {
        return $this->invoke(...$args);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function invoke($value)
    {
        foreach ($this->then as $callback) {
            $value = $this->process->await($callback($value));
        }

        return $value;
    }
}
