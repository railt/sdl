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
use Railt\Reflection\Definition\UnionDefinition;
use Railt\SDL\Compiler\Ast\Definition\UnionDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class UnionBuilder
 */
class UnionBuilder extends Builder
{
    /**
     * @param RuleInterface|UnionDefinitionNode $rule
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $union = new UnionDefinition($parent->getDocument(), $rule->getTypeName());

        $union->withOffset($rule->getOffset());
        $union->withDescription($rule->getDescription());

        foreach ($rule->getUnitedTypes() as $ast) {
            $union->withDefinition($ast->getTypeName());
        }

        foreach ($rule->getDirectives() as $ast) {
            $union->withDirective($this->dependent($ast, $union));
        }

        return $union;
    }
}
