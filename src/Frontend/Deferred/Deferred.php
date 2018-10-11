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
 * Class Deferred
 */
class Deferred implements DeferredInterface
{
    /**
     * @var \Closure[]
     */
    private $then = [];

    /**
     * Deferred constructor.
     * @param \Closure $then
     */
    public function __construct(\Closure $then = null)
    {
        if ($then) {
            $this->then($then);
        }
    }

    /**
     * @param \Closure $then
     * @return Deferred|$this
     */
    public function then(\Closure $then): DeferredInterface
    {
        $this->then[] = $then;

        return $this;
    }

    /**
     * @param mixed ...$args
     * @return \Generator|mixed
     */
    public function __invoke(...$args)
    {
        return $this->invoke(...$args);
    }

    /**
     * @param array $args
     * @return \Generator|mixed
     */
    public function invoke(...$args): \Generator
    {
        $result = $args[0] ?? null;

        foreach ($this->then as $callback) {
            yield from $output = $this->response($callback(...$args));

            $args[0] = $result = $output->getReturn();
        }

        return $result;
    }

    /**
     * @param mixed $result
     * @return \Generator
     */
    private function response($result): \Generator
    {
        if ($result instanceof \Generator) {
            yield from $result;

            return $result->getReturn();
        }

        return $result;
    }
}
