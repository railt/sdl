<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

/**
 * Class Process
 */
class Process
{
    /**
     * @var \SplQueue|\Closure[]
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
     * @param \Closure $deferred
     */
    public function async(\Closure $deferred): void
    {
        $this->deferred[] = $deferred;
    }

    /**
     * @param array $args
     * @return void
     */
    public function run(...$args): void
    {
        while ($this->deferred->count()) {
            $callback = $this->deferred->pop();
            $callback(...$args);
        }
    }
}
