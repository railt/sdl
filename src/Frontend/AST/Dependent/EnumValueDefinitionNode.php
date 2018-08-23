<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Dependent;

use Railt\Reflection\Contracts\TypeInterface;
use Railt\Reflection\Type;
use Railt\SDL\Frontend\AST\ProvidesTypeHint;
use Railt\SDL\Frontend\AST\Support\TypeHintProvider;

/**
 * Class EnumValueDefinitionNode
 */
class EnumValueDefinitionNode extends DependentTypeDefinitionNode implements ProvidesTypeHint
{
    use TypeHintProvider;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::ENUM_VALUE);
    }
}
