<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\AbstractDefinition;
use Railt\Reflection\Contracts\Definition;

/**
 * Class OffsetSystem
 */
class OffsetSystem extends System
{
    /**
     * @param Definition $definition
     * @param RuleInterface $ast
     */
    public function resolve(Definition $definition, RuleInterface $ast): void
    {
        if ($definition instanceof AbstractDefinition) {
            $definition->withOffset($ast->getOffset());
        }
    }
}
