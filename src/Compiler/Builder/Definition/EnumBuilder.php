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
use Railt\Reflection\Definition\EnumDefinition;
use Railt\SDL\Compiler\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Compiler\Ast\Definition\EnumDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class EnumBuilder
 */
class EnumBuilder extends Builder
{
    /**
     * @param RuleInterface|EnumDefinitionNode $rule
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $enum = new EnumDefinition($parent->getDocument(), $rule->getTypeName());
        $enum->withOffset($rule->getOffset());
        $enum->withDescription($rule->getDescription());

        foreach ($rule->getDirectives() as $ast) {
            $enum->withDirective($this->dependent($ast, $enum));
        }

        foreach ($rule->getEnumValues() as $enumValue) {
            $enum->withValue($this->dependent($enumValue, $enum));
        }

        return $enum;
    }
}
