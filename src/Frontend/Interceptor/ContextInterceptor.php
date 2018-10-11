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
 * Class ContextInterceptor
 */
class ContextInterceptor implements InterceptorInterface
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $value instanceof ContextInterface;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $value
     * @return array
     */
    public function resolve(ContextInterface $context, $value): array
    {
        return [$value, $value];
    }
}
