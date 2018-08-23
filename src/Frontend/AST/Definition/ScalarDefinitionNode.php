<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition;

use Railt\Reflection\Contracts\TypeInterface;
use Railt\Reflection\Type;

/**
 * Class ScalarDefinitionNode
 */
class ScalarDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::SCALAR);
    }
}
