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
use Railt\Reflection\Definition\UnionDefinition;

/**
 * Class UnionDefinitionNode
 */
class UnionDefinitionNode extends TypeDefinitionNode
{
    /**
     * @param Definition $parent
     * @return Definition|UnionDefinition
     */
    public function resolve(Definition $parent): Definition
    {
        return new UnionDefinition($parent->getDocument(), $this->getFullName());
    }
}
