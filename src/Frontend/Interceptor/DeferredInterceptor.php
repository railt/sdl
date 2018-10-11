<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Interceptor;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Deferred\DeferredInterface;
use Railt\SDL\Frontend\Deferred\DeferredCollection;

/**
 * Class DeferredInterceptor
 */
class DeferredInterceptor implements InterceptorInterface
{
    /**
     * @var DeferredCollection
     */
    private $storage;

    /**
     * DeferredInterceptor constructor.
     * @param DeferredCollection $storage
     */
    public function __construct(DeferredCollection $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param RuleInterface $value
     * @return bool
     */
    public function match($value): bool
    {
        return $value instanceof DeferredInterface;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $value
     * @return array
     */
    public function resolve(ContextInterface $context, $value): array
    {
        $this->storage->add($value);

        return [$context, $value];
    }
}
