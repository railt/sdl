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
use Railt\SDL\Frontend\AST\ProvidesArgumentNodes;
use Railt\SDL\Frontend\AST\ProvidesTypeHint;
use Railt\SDL\Frontend\AST\Support\ArgumentsProvider;
use Railt\SDL\Frontend\AST\Support\TypeHintProvider;

/**
 * Class FieldDefinitionNode
 */
class FieldDefinitionNode extends DependentTypeDefinitionNode implements ProvidesArgumentNodes, ProvidesTypeHint
{
    use ArgumentsProvider;
    use TypeHintProvider;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::FIELD);
    }
}
