<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\DirectiveDefinition;

/**
 * Class DirectiveDefinitionNode
 */
class DirectiveDefinitionNode extends TypeDefinitionNode
{
    /**
     * @param Definition $context
     * @return Definition|DirectiveDefinition
     */
    public function resolve(Definition $context): Definition
    {
        return new DirectiveDefinition($context->getDocument(), $this->getFullName());
    }
}
