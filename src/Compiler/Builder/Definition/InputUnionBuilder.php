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
use Railt\Reflection\Definition\InputUnionDefinition;
use Railt\SDL\Compiler\Ast\Definition\UnionDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class InputUnionBuilder
 */
class InputUnionBuilder extends Builder
{
    /**
     * @param RuleInterface|UnionDefinitionNode $rule
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $inputUnion = new InputUnionDefinition($parent->getDocument(), $rule->getTypeName());

        $inputUnion->withOffset($rule->getOffset());
        $inputUnion->withDescription($rule->getDescription());

        $this->when->runtime(function () use ($rule, $inputUnion) {
            foreach ($rule->getDirectives() as $ast) {
                $inputUnion->withDirective($this->dependent($ast, $inputUnion));
            }
        });

        $this->when->resolving(function () use ($rule, $inputUnion) {
            foreach ($rule->getUnitedTypes() as $ast) {
                $inputUnion->withDefinition($ast->getTypeName());
            }
        });

        return $inputUnion;
    }
}
