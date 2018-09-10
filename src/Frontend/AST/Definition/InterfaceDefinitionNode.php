<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition;

use Railt\SDL\Frontend\Ast\Definition\Provider\FieldNodesProvider;
use Railt\SDL\Frontend\Ast\Definition\Provider\InterfacesProvider;

/**
 * Class InterfaceDefinitionNode
 */
class InterfaceDefinitionNode extends TypeDefinitionNode
{
    use InterfacesProvider;
    use FieldNodesProvider;
}
