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
use Railt\Reflection\Definition\Behaviour\HasArguments;
use Railt\SDL\Ast\ProvidesArgumentNodes;
use Railt\SDL\Compiler\System\System;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class ArgumentSystem
 */
class ArgumentSystem extends System
{
    /**
     * @param Definition|HasArguments $definition
     * @param RuleInterface $ast
     */
    public function resolve(Definition $definition, RuleInterface $ast): void
    {
        if ($ast instanceof ProvidesArgumentNodes) {
            foreach ($ast->getArgumentNodes() as $child) {
                $this->deferred(function () use ($definition, $child) {
                    /** @var Definition\Dependent\ArgumentDefinition $argument */
                    $argument = $this->process->build($child, $definition);

                    $this->linker(function () use ($definition, $argument) {
                        if ($definition->hasArgument($argument->getName())) {
                            throw $this->redeclareException($argument);
                        }

                        $definition->withArgument($argument);
                    });
                });
            }
        }
    }
}
