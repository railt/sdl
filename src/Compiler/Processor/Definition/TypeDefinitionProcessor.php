<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Processor\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\AbstractTypeDefinition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Invocation\Dependent\ArgumentInvocation;
use Railt\Reflection\Invocation\DirectiveInvocation;
use Railt\SDL\Compiler\Ast\Common\DescriptionProvider;
use Railt\SDL\Compiler\Ast\Common\DirectivesProvider;
use Railt\SDL\Compiler\Ast\Dependent\DirectiveNode;
use Railt\SDL\Compiler\Processor\BaseProcessor;

/**
 * Class TypeDefinitionProcessor
 */
abstract class TypeDefinitionProcessor extends BaseProcessor
{
    /**
     * @param RuleInterface|DescriptionProvider $ast
     * @param TypeDefinition|AbstractTypeDefinition $definition
     */
    protected function processDefinition(RuleInterface $ast, TypeDefinition $definition): void
    {
        $this->immediately(function () use ($ast, $definition): void {
            $this->transaction($definition, function () use ($ast, $definition) {
                $definition->withOffset($ast->getOffset());
                $definition->withDescription($ast->getDescription());
            });
        });
    }

    /**
     * @param RuleInterface|DirectivesProvider $ast
     * @param TypeDefinition|AbstractTypeDefinition $definition
     */
    protected function processDirectives(RuleInterface $ast, TypeDefinition $definition): void
    {
        $this->future(function () use ($ast, $definition): void {
            $this->transaction($definition, function () use ($ast, $definition) {
                foreach ($ast->getDirectives() as $child) {
                    $directive = $child->getTypeInvocation($this->document);

                    $this->processDirective($child, $directive);

                    $definition->withDirective($directive);
                }
            });
        });
    }

    /**
     * @param DirectiveNode $ast
     * @param DirectiveInvocation $directive
     * @throws \Railt\SDL\Exception\CompilerException
     */
    private function processDirective(DirectiveNode $ast, DirectiveInvocation $directive): void
    {
        $this->transaction($directive, function () use ($ast, $directive) {
            foreach ($ast->getDirectiveArguments() as $child) {
                $argument = $child->getTypeInvocation($directive);

                $this->processDirectiveArguments($argument);

                $directive->withArgument($argument);
            }
        });
    }

    /**
     * @param ArgumentInvocation $argument
     * @throws \Railt\SDL\Exception\CompilerException
     */
    private function processDirectiveArguments(ArgumentInvocation $argument): void
    {
        $this->transaction($argument, function () use ($argument) {
            // TODO
        });
    }
}
