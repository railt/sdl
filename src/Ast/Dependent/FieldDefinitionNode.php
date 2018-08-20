<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Dependent;

use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Definition\Dependent\FieldDefinition;
use Railt\SDL\Ast\ProvidesArgumentNodes;
use Railt\SDL\Ast\ProvidesTypeHint;
use Railt\SDL\Ast\Support\ArgumentsProvider;
use Railt\SDL\Ast\Support\TypeHintProvider;

/**
 * Class FieldDefinitionNode
 */
class FieldDefinitionNode extends DependentTypeDefinitionNode implements ProvidesArgumentNodes, ProvidesTypeHint
{
    use ArgumentsProvider;
    use TypeHintProvider;

    /**
     * @param Definition|TypeDefinition $parent
     * @return Definition|FieldDefinition
     */
    public function resolve(Definition $parent): Definition
    {
        \assert($parent instanceof Definition\Behaviour\ProvidesFields);

        return new FieldDefinition($parent, $this->getFullName(), $this->getTypeHintNode()->getFullName());
    }
}
