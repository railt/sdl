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

/**
 * Class RuleInterceptor
 */
class RuleInterceptor implements InterceptorInterface
{
    /**
     * @var \Closure
     */
    private $reducer;

    /**
     * RuleInterceptor constructor.
     * @param \Closure $reduce
     */
    public function __construct(\Closure $reduce)
    {
        $this->reducer = $reduce;
    }

    /**
     * @param RuleInterface $value
     * @return bool
     */
    public function match($value): bool
    {
        return $value instanceof RuleInterface;
    }

    /**
     * @param ContextInterface $context
     * @param mixed $value
     * @return array
     */
    public function resolve(ContextInterface $context, $value): array
    {
        return ($this->reducer)($context, $value);
    }
}
