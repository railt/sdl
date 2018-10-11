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
 * Class Interceptor
 */
class Interceptor implements InterceptorInterface
{
    /**
     * @var callable
     */
    private $matcher;

    /**
     * @var callable
     */
    private $resolver;

    /**
     * Interceptor constructor.
     * @param callable $matcher
     * @param callable $resolver
     */
    public function __construct(callable $matcher, callable $resolver)
    {
        $this->matcher = $matcher;
        $this->resolver = $resolver;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return (bool)($this->matcher)($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function invoke($value)
    {
        return ($this->resolver)($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function __invoke($value)
    {
        return $this->invoke($value);
    }
}
