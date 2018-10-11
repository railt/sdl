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
use Railt\SDL\Frontend\Definition\InvocationInterface;
use Railt\SDL\Frontend\Definition\Storage;

/**
 * Class InvocationInterceptor
 */
class InvocationInterceptor implements InterceptorInterface
{
    /**
     * @var Storage
     */
    private $types;

    /**
     * InvocationInterceptor constructor.
     * @param Storage $types
     */
    public function __construct(Storage $types)
    {
        $this->types = $types;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $value instanceof InvocationInterface;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $value
     * @return array
     */
    public function resolve(ContextInterface $context, $value): array
    {
        $value = $this->types->invoke($value);

        return [$context, $value];
    }
}
