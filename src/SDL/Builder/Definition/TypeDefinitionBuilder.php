<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder\Definition;

use Railt\Reflection\AbstractTypeDefinition;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Invocation\Behaviour\ProvidesDirectives;
use Railt\SDL\Builder\Builder;
use Railt\SDL\Builder\Utils;

/**
 * Class TypeDefinitionBuilder
 */
abstract class TypeDefinitionBuilder extends Builder
{
    /**
     * @param Definition|AbstractTypeDefinition $definition
     * @return Definition
     * @throws \Railt\SDL\Exception\TypeException
     */
    protected function bind(Definition $definition): Definition
    {
        if ($definition instanceof TypeDefinition) {
            $definition->withDescription(Utils::findDescription($this->ast));
        }

        if ($definition instanceof ProvidesDirectives) {
            foreach ($this->ast->getChildren() as $directive) {
                if ($directive->is('Directive')) {
                    $this->make($definition, $directive);
                }
            }
        }

        return parent::bind($definition);
    }
}
