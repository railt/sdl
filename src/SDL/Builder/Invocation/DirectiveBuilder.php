<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder\Invocation;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Invocation\Behaviour\ProvidesDirectives;
use Railt\Reflection\Definition\DirectiveDefinition;
use Railt\Reflection\Invocation\Behaviour\HasDirectives;
use Railt\Reflection\Invocation\DirectiveInvocation;
use Railt\Reflection\Type;
use Railt\SDL\Exception\SyntaxException;
use Railt\SDL\Exception\TypeException;
use Railt\SDL\Exception\TypeNotFoundException;

/**
 * Class DirectiveBuilder
 * @property ProvidesDirectives|HasDirectives $definition
 */
class DirectiveBuilder extends TypeInvocationBuilder
{
    /**
     * @return Definition
     * @throws SyntaxException
     * @throws TypeException
     */
    public function build(): Definition
    {
        /** @var DirectiveInvocation $directive */
        $directive = $this->bind(new DirectiveInvocation($this->document, $this->getName()));

        foreach ($this->ast->getChildren() as $argument) {
            if ($argument->is('Argument')) {
                $this->buildArgument($directive, $argument);
            }
        }

        $this->async(function () use ($directive): void {
            $this->loadDirective($directive);

            $this->definition->withDirective($directive);
        });

        return $directive;
    }

    private function buildArgument(DirectiveInvocation $directive, RuleInterface $rule): void
    {
        echo $rule;
        die;
    }

    /**
     * @param DirectiveInvocation $directive
     * @throws TypeException
     * @throws TypeNotFoundException
     */
    private function loadDirective(DirectiveInvocation $directive): void
    {
        /** @var DirectiveDefinition $definition */
        $definition = $this->load($directive->getName(), $directive);

        $this->checkType($directive, $definition);
        $this->loadDefaultArguments($directive, $definition);
        $this->checkMissingArguments($directive, $definition);
    }

    /**
     * @param DirectiveInvocation $directive
     * @param Definition $definition
     * @throws TypeException
     */
    private function checkType(DirectiveInvocation $directive, Definition $definition): void
    {
        if (! $definition::getType()->is(Type::DIRECTIVE)) {
            $error = \sprintf('Can not use %s as directive', $definition);

            $exception = new TypeException($error);
            $exception->throwsIn($directive->getFile(), $directive->getLine(), $directive->getColumn());

            throw $exception;
        }
    }

    /**
     * @param DirectiveInvocation $directive
     * @param DirectiveDefinition $definition
     */
    private function loadDefaultArguments(DirectiveInvocation $directive, DirectiveDefinition $definition): void
    {
        foreach ($definition->getArguments() as $argument) {
            if ($argument->hasDefaultValue()) {
                $directive->withArgument($argument->getName(), $argument->getDefaultValue());
            }
        }
    }

    private function checkMissingArguments(DirectiveInvocation $directive, DirectiveDefinition $definition): void
    {
    }
}
