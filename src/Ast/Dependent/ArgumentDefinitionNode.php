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
use Railt\Reflection\Definition\Dependent\ArgumentDefinition;
use Railt\SDL\Ast\ProvidesTypeHint;
use Railt\SDL\Ast\Support\TypeHintProvider;

/**
 * Class ArgumentDefinitionNode
 */
class ArgumentDefinitionNode extends DependentTypeDefinitionNode implements ProvidesTypeHint
{
    use TypeHintProvider;

    /**
     * @param Definition|TypeDefinition $parent
     * @return Definition|ArgumentDefinition
     */
    public function resolve(Definition $parent): Definition
    {
        \assert($parent instanceof Definition\Behaviour\ProvidesArguments);

        return new ArgumentDefinition($parent, $this->getFullName(), $this->getTypeHintNode()->getFullName());
    }
}
