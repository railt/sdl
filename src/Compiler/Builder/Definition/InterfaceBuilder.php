<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\ObjectDefinition;
use Railt\SDL\Compiler\Ast\Definition\InterfaceDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class InterfaceBuilder
 */
class InterfaceBuilder extends Builder
{
    /**
     * @param RuleInterface|InterfaceDefinitionNode $rule
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $interface = new ObjectDefinition($parent->getDocument(), $rule->getTypeName());

        $interface->withOffset($rule->getOffset());
        $interface->withDescription($rule->getDescription());

        foreach ($rule->getFields() as $ast) {
            $interface->withField($this->dependent($ast, $interface));
        }

        foreach ($rule->getImplementations() as $child) {
            $interface->withInterface($child->getTypeName());
        }

        foreach ($rule->getDirectives() as $ast) {
            $interface->withDirective($this->dependent($ast, $interface));
        }

        return $interface;
    }
}
