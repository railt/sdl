<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Definition;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\Dependent\DirectiveLocation;
use Railt\Reflection\Definition\DirectiveDefinition;
use Railt\SDL\Compiler\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;
use Railt\SDL\Exception\TypeConflictException;

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

        $this->buildLocations($directive, $rule);
        $this->buildArguments($directive, $rule);

        return $directive;
    }

    /**
     * @param DirectiveDefinition $directive
     * @param DirectiveDefinitionNode $rule
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function buildArguments(DirectiveDefinition $directive, DirectiveDefinitionNode $rule): void
    {
        foreach ($rule->getArguments() as $ast) {
            /** @var Definition\Dependent\ArgumentDefinition $argument */
            $argument = $this->dependent($ast, $directive);

            $directive->withArgument($argument);
        }
    }

    /**
     * @param DirectiveDefinition $directive
     * @param DirectiveDefinitionNode $rule
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function buildLocations(DirectiveDefinition $directive, DirectiveDefinitionNode $rule): void
    {
        $locations = \array_merge(
            DirectiveLocation::EXECUTABLE_LOCATIONS,
            DirectiveLocation::SDL_LOCATIONS
        );

        /** @var LeafInterface $ast */
        foreach ($rule->getLocations() as $ast => $name) {
            $location = new DirectiveLocation($directive, $name);
            $location->withOffset($ast->getOffset());

            if (! \in_array($name, $locations, true)) {
                $error = \sprintf('Invalid directive location %s, only one of {%s} allowed',
                    $location, \implode(', ', $locations));
                throw (new TypeConflictException($error))->throwsIn($directive->getFile(), $ast->getOffset());
            }

            $directive->withLocation($location);
        }
    }
}
