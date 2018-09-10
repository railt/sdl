<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition;

use Railt\SDL\Frontend\Ast\Definition\Provider\ArgumentNodesProvider;
use Railt\SDL\Frontend\Ast\Definition\Provider\DependentNameProvider;
use Railt\SDL\Frontend\Ast\Definition\Provider\ProvidesTypeHint;

/**
 * Class FieldDefinitionNode
 */
class FieldDefinitionNode extends TypeDefinitionNode
{
    use DependentNameProvider;
    use ArgumentNodesProvider;
    use ProvidesTypeHint;
}
