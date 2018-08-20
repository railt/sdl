<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System\Provider;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\DirectiveDefinition;
use Railt\Reflection\Contracts\Invocation\Behaviour\ProvidesDirectives;
use Railt\Reflection\Contracts\Invocation\DirectiveInvocation;
use Railt\Reflection\Invocation\Behaviour\HasDirectives;
use Railt\SDL\Ast\ProvidesDirectiveNodes;
use Railt\SDL\Compiler\System\System;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class DirectivesSystem
 */
class DirectivesSystem extends System
{
    /**
     * @param Definition|HasDirectives $definition
     * @param RuleInterface $ast
     */
    public function resolve(Definition $definition, RuleInterface $ast): void
    {
        if ($definition instanceof ProvidesDirectives && $ast instanceof ProvidesDirectiveNodes) {
            foreach ($ast->getDirectiveNodes() as $child) {
                $this->deferred(function () use ($definition, $child) {
                    /** @var DirectiveInvocation $directive */
                    $directive = $this->process->build($child, $definition);

                    $this->linker(function () use ($definition, $directive) {
                        $this->validateDefinition($directive);

                        $definition->withDirective($directive);
                    });
                });
            }
        }
    }

    /**
     * @param DirectiveInvocation $directive
     * @throws \Railt\SDL\Exception\CompilerException
     */
    private function validateDefinition(DirectiveInvocation $directive): void
    {
        $definition = $directive->getDefinition();

        if (! $definition instanceof DirectiveDefinition) {
            $error = \sprintf('Can not use %s as directive', $definition);
            throw (new TypeConflictException($error))->in($directive);
        }
    }
}
