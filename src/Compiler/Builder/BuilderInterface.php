<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;

/**
 * Interface BuilderInterface
 */
interface BuilderInterface
{
    /**
     * @param RuleInterface $rule
     * @param Definition $parent
     * @return Definition
     */
    public function build(RuleInterface $rule, Definition $parent): Definition;
}
