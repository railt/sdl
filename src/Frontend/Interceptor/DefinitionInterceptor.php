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
use Railt\SDL\Frontend\Deferred\Deferred;
use Railt\SDL\Frontend\Definition\DefinitionInterface;
use Railt\SDL\Frontend\Definition\Storage;

/**
 * Class DefinitionInterceptor
 */
class DefinitionInterceptor implements InterceptorInterface
{
    /**
     * @var Storage
     */
    private $definitions;

    /**
     * DefinitionInterceptor constructor.
     * @param Storage $definitions
     */
    public function __construct(Storage $definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $value instanceof DefinitionInterface;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $value
     * @return array
     */
    public function resolve(ContextInterface $context, $value): array
    {
        $deferred = new Deferred();

        $this->definitions->remember($value, $deferred);

        return [$context, $deferred];
    }
}
