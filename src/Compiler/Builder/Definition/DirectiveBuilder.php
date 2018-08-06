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
use Railt\Reflection\Definition\Dependent\DirectiveLocation;
use Railt\Reflection\Definition\DirectiveDefinition;
use Railt\SDL\Compiler\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class DirectiveBuilder
 */
class DirectiveBuilder extends Builder
{
    /**
     * @param RuleInterface|DirectiveDefinitionNode $rule
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $directive = new DirectiveDefinition($parent->getDocument(), $rule->getTypeName());
        $directive->withOffset($rule->getOffset());
        $directive->withDescription($rule->getDescription());

        foreach ($rule->getLocations() as $name) {
            $location = new DirectiveLocation($directive, $name);

            $directive->withLocation($location);
        }

        foreach ($rule->getArguments() as $ast) {
            $directive->withArgument($this->dependent($ast, $directive));
        }

        return $directive;
    }
}
