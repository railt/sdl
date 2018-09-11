<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition;

use Railt\SDL\Frontend\AST\Definition\Provider\FieldNodesProvider;
use Railt\SDL\Frontend\AST\Definition\Provider\InterfacesProvider;

/**
 * Class ObjectDefinitionNode
 */
class ObjectDefinitionNode extends TypeDefinitionNode
{
    use InterfacesProvider;
    use FieldNodesProvider;
}
