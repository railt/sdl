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
use Railt\Reflection\Invocation\DirectiveInvocation as Invocation;
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
        $directive = new Invocation($parent->getDocument(), $rule->getDirectiveName());
        $directive->withOffset($rule->getOffset());

        $this->when->resolving(function () use ($rule, $directive): void {
            $definition = $this->loadDefinition($directive, $rule);

            $this->buildArguments($directive, $definition, $rule);

            $this->validateUncompletedArguments($directive, $definition);
        });

        return $directive;
    }

    /**
     * @param Invocation $directive
     * @param RuleInterface $rule
     * @return DirectiveDefinition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function loadDefinition(Invocation $directive, RuleInterface $rule): DirectiveDefinition
    {
        /** @var DirectiveDefinition $definition */
        $definition = $directive->getDefinition();

        if (! ($definition instanceof Definition\DirectiveDefinition)) {
            $error = '%s should be a Directive, but %s given';
            throw (new TypeConflictException(\sprintf($error, $directive,
                $definition)))->throwsIn($directive->getFile(), $rule->getOffset());
        }

        return $definition;
    }

    /**
     * @param Invocation $call
     * @param DirectiveDefinition $def
     * @param RuleInterface|DirectiveNode $rule
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function buildArguments(Invocation $call, DirectiveDefinition $def, RuleInterface $rule): void
    {
        /** @var DirectiveArgumentNode $ast */
        foreach ($rule->getDirectiveArguments() as $ast) {
            $name = $ast->getArgumentName();
            $argument = $def->getArgument($name);

            if ($argument === null) {
                $error = 'Directive %s does not provide argument %s';
                throw (new TypeConflictException(\sprintf($error, $def, $name)))->throwsIn($call->getFile(),
                        $ast->getOffset());
            }

            $value = $this->valueOf($argument, $ast->getArgumentValue());

            $call->withArgument($name, $value);
        }
    }

    /**
     * @param Invocation $directive
     * @param DirectiveDefinition $definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function validateUncompletedArguments(Invocation $directive, DirectiveDefinition $definition): void
    {
        foreach ($definition->getArguments() as $argument) {
            if ($directive->hasArgument($argument->getName())) {
                continue;
            }

            if (! $argument->hasDefaultValue()) {
                $error = 'Missing value for required argument %s of %s';
                throw (new TypeConflictException(\sprintf($error, $argument,
                    $directive)))->throwsIn($directive->getFile(), $directive->getLine(), $directive->getColumn());
            }
        }
    }
}
