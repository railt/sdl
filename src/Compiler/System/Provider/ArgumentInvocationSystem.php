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
use Railt\Reflection\Contracts\Invocation\Behaviour\ProvidesArguments;
use Railt\SDL\Compiler\System\System;

/**
 * Class ArgumentInvocationSystem
 */
class ArgumentInvocationSystem extends System
{
    /**
     * @param Definition $definition
     * @param RuleInterface $ast
     */
    public function resolve(Definition $definition, RuleInterface $ast): void
    {
        if ($definition instanceof ProvidesArguments) {
            // TODO
        }
    }
}
