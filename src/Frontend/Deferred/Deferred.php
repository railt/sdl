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
     * @var ContextInterface
     */
    private $ctx;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * Deferred constructor.
     * @param ContextInterface $ctx
     * @param \Closure $then
     */
    public function __construct(ContextInterface $ctx, \Closure $then = null)
    {
        $this->ctx = $ctx;

        if ($then) {
            $this->then($then);
        }
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return DeferredInterface
     */
    public function definedIn(int $offset): DeferredInterface
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface
    {
        return $this->ctx;
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
     * @param mixed $value
     * @return \Generator|mixed|null
     */
    public function __invoke($value)
    {
        return $this->invoke($value);
    }

    /**
     * @param array $args
     * @return \Generator|mixed
     */
    public function invoke(...$args): \Generator
    {
        $result = $args[0] ?? null;

        foreach ($this->then as $callback) {
            yield from $output = $this->response($callback($this->ctx, ...$args));

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
