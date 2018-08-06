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
use Railt\Reflection\Invocation\DirectiveInvocation;
use Railt\SDL\Compiler\Ast\Invocation\DirectiveNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class DirectiveInvocationBuilder
 */
class DirectiveBuilder extends Builder
{
    /**
     * @param RuleInterface|DirectiveNode $rule
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $directive = new DirectiveInvocation($parent->getDocument(), $rule->getDirectiveName());
        $directive->withOffset($rule->getOffset());

        foreach ($rule->getDirectiveArguments() as $ast) {
            $directive->withArgument($this->dependent($ast, $directive));
        }

        return $directive;
    }
}
