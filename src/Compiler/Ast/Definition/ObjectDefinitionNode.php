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

/**
 * Class ObjectDefinitionNode
 */
class ObjectDefinitionNode extends TypeDefinitionNode
{
    use FieldsProviders;
    use ImplementationsProvider;
}
