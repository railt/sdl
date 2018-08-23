<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition;

use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\TypeInterface;
use Railt\Reflection\Definition\InterfaceDefinition;
use Railt\Reflection\Type;
use Railt\SDL\Frontend\AST\ProvidesFieldNodes;
use Railt\SDL\Frontend\AST\ProvidesInterfaceNodes;
use Railt\SDL\Frontend\AST\Support\FieldsProvider;
use Railt\SDL\Frontend\AST\Support\InterfacesProvider;

/**
 * Class InterfaceDefinitionNode
 */
class InterfaceDefinitionNode extends TypeDefinitionNode implements ProvidesFieldNodes, ProvidesInterfaceNodes
{
    use FieldsProvider;
    use InterfacesProvider;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::INTERFACE);
    }
}
