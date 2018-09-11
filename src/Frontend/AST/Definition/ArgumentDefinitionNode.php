<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition;

use Railt\SDL\Frontend\AST\Definition\Provider\DefaultValueProvider;
use Railt\SDL\Frontend\AST\Definition\Provider\DependentNameProvider;
use Railt\SDL\Frontend\AST\Definition\Provider\ProvidesTypeHint;

/**
 * Class ArgumentDefinitionNode
 */
class ArgumentDefinitionNode extends TypeDefinitionNode
{
    use ProvidesTypeHint;
    use DefaultValueProvider;
    use DependentNameProvider;
}
