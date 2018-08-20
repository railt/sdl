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
use Railt\Reflection\Definition\SchemaDefinition;

/**
 * Class SchemaDefinitionNode
 */
class SchemaDefinitionNode extends TypeDefinitionNode
{
    /**
     * @param Definition $parent
     * @return Definition|SchemaDefinition
     */
    public function resolve(Definition $parent): Definition
    {
        return new SchemaDefinition($parent->getDocument(), $this->getFullName());
    }
}
