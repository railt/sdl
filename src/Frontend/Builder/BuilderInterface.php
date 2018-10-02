<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Context\ContextInterface;

/**
 * Interface BuilderInterface
 */
interface BuilderInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool;

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return mixed
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule);
}
