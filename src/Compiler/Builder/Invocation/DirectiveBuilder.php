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
use Railt\Reflection\Definition\DirectiveDefinition;
use Railt\Reflection\Invocation\DirectiveInvocation;
use Railt\SDL\Compiler\Ast\Invocation\DirectiveArgumentNode;
use Railt\SDL\Compiler\Ast\Invocation\DirectiveNode;
use Railt\SDL\Compiler\Builder\Builder;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class DirectiveInvocationBuilder
 */
class DirectiveBuilder extends Builder
{
    /**
     * @param RuleInterface|DirectiveNode $rule
     * @param Definition $parent
     * @return Definition
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $directive = new DirectiveInvocation($parent->getDocument(), $rule->getDirectiveName());
        $directive->withOffset($rule->getOffset());

        $this->when->resolving(function () use ($rule, $directive): void {
            /** @var DirectiveDefinition $definition */
            $definition = $directive->getDefinition();

            if (! ($definition instanceof Definition\DirectiveDefinition)) {
                $error = '%s should be a Directive, but %s given';
                throw (new TypeConflictException(\sprintf($error, $directive, $definition)))
                    ->throwsIn($directive->getFile(), $rule->getOffset());
            }

            /** @var DirectiveArgumentNode $ast */
            foreach ($rule->getDirectiveArguments() as $ast) {
                $name = $ast->getArgumentName();

                if (! $definition->hasArgument($name)) {
                    $error = 'Directive %s does not provide argument %s';
                    throw (new TypeConflictException(\sprintf($error, $definition, $name)))
                        ->throwsIn($directive->getFile(), $ast->getOffset());
                }

                /** @var Definition\Dependent\ArgumentDefinition $argument */
                $argument = $definition->getArgument($name);

                $directive->withArgument($name, $this->valueOf($argument, $ast->getArgumentValue()));
            }
        });

        return $directive;
    }
}
