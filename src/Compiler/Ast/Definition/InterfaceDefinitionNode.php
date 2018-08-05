<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\SDL\Compiler\Ast\Common\FieldsProviders;
use Railt\SDL\Compiler\Ast\Common\ImplementationsProvider;
use Railt\SDL\Compiler\Ast\TypeNameNode;

/**
 * Class InterfaceDefinitionNode
 */
class InterfaceDefinitionNode extends TypeDefinitionNode
{
    use FieldsProviders;
    use ImplementationsProvider;
}
