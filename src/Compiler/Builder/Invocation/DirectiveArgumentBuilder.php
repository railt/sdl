<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Invocation;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Invocation\Dependent\ArgumentInvocation;
use Railt\Reflection\Invocation\DirectiveInvocation;
use Railt\SDL\Compiler\Ast\Invocation\DirectiveArgumentNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class DirectiveArgumentBuilder
 */
class DirectiveArgumentBuilder extends Builder
{
    /**
     * @param RuleInterface|DirectiveArgumentNode $rule
     * @param Definition|DirectiveInvocation $parent
     * @return Definition
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $value = $rule->getArgumentValue();

        $argument = new ArgumentInvocation($parent, $rule->getArgumentName(), $value->toPrimitive());
        $argument->withOffset($rule->getOffset());

        return $argument;
    }
}
