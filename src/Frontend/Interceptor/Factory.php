<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Interceptor;

use Railt\SDL\Frontend\Context\ContextInterface;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @var array|InterceptorInterface[]
     */
    private $interceptors;

    /**
     * Factory constructor.
     * @param array|InterceptorInterface $interceptors
     */
    public function __construct(array $interceptors = [])
    {
        $this->interceptors = $interceptors;
    }

    /**
     * @param array|InterceptorInterface $interceptors
     * @return Factory
     */
    public static function create(array $interceptors = []): Factory
    {
        return new static($interceptors);
    }

    /**
     * @param InterceptorInterface ...$interceptors
     * @return Factory
     */
    public function add(InterceptorInterface ...$interceptors): Factory
    {
        foreach ($interceptors as $interceptor) {
            $this->interceptors[] = $interceptor;
        }

        return $this;
    }

    /**
     * @param ContextInterface $ctx
     * @param mixed $value
     * @return array
     */
    public function resolve(ContextInterface $ctx, $value): array
    {
        foreach ($this->interceptors as $interceptor) {
            if ($interceptor->match($value)) {
                return $interceptor->resolve($ctx, $value);
            }
        }

        return [$ctx, $value];
    }
}
