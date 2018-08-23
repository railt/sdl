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
use Railt\Reflection\Definition\UnionDefinition;
use Railt\Reflection\Type;

/**
 * Class UnionDefinitionNode
 */
class UnionDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::UNION);
    }
}
