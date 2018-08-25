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
use Railt\SDL\Frontend\IR\Value\StringValue;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Class ArgumentDefinitionNode
 */
class ArgumentDefinitionNode extends DependentTypeDefinitionNode implements ProvidesTypeHint
{
    use TypeHintProvider;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::ARGUMENT);
    }

    /**
     * @return ValueInterface
     */
    public function getKey(): ValueInterface
    {
        return new StringValue($this->getFullName(), $this->getOffset());
    }
}
