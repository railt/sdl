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
use Railt\Reflection\Definition\InterfaceDefinition;
use Railt\SDL\Ast\ProvidesFieldNodes;
use Railt\SDL\Ast\ProvidesInterfaceNodes;
use Railt\SDL\Ast\Support\FieldsProvider;
use Railt\SDL\Ast\Support\InterfacesProvider;

/**
 * Class InterfaceDefinitionNode
 */
class InterfaceDefinitionNode extends TypeDefinitionNode implements ProvidesFieldNodes, ProvidesInterfaceNodes
{
    use FieldsProvider;
    use InterfacesProvider;

    /**
     * @param Definition $parent
     * @return Definition|InterfaceDefinition
     */
    public function resolve(Definition $parent): Definition
    {
        return new InterfaceDefinition($parent->getDocument(), $this->getFullName());
    }
}
