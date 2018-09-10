<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition;

use Railt\Parser\Ast\Rule;
use Railt\SDL\Frontend\Ast\Definition\Provider\DescriptionProvider;
use Railt\SDL\Frontend\Ast\Definition\Provider\TypeNameProvider;

/**
 * Class TypeDefinitionNode
 */
abstract class TypeDefinitionNode extends Rule
{
    use TypeNameProvider;
    use DescriptionProvider;
}
