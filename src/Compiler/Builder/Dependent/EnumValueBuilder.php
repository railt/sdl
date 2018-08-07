<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Dependent;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Type as TypeInterface;
use Railt\Reflection\Definition\Dependent\EnumValueDefinition;
use Railt\Reflection\Definition\EnumDefinition;
use Railt\Reflection\Type;
use Railt\SDL\Compiler\Ast\Dependent\EnumValueDefinitionNode;
use Railt\SDL\Compiler\Ast\TypeHintNode;
use Railt\SDL\Compiler\Builder\Builder;
use Railt\SDL\Compiler\Builder\Virtual\TypeHint;

/**
 * Class EnumValueBuilder
 */
class EnumValueBuilder extends Builder
{
    /**
     * @param RuleInterface|EnumValueDefinitionNode $rule
     * @param Definition|EnumDefinition $parent
     * @return Definition
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $value = new EnumValueDefinition($parent, $rule->getValueName());
        $value->withOffset($rule->getOffset());
        $value->withDescription($rule->getDescription());

        $this->when->runtime(function () use ($rule, $value): void {
            if ($hint = $rule->getTypeHint()) {
                $value->withValue($this->valueOf($this->virtualTypeHint($value, $hint), $rule->getValue()));
            }

            foreach ($rule->getDirectives() as $ast) {
                $value->withDirective($this->dependent($ast, $value));
            }
        });

        return $value;
    }

    /**
     * @param EnumValueDefinition $value
     * @param TypeHintNode $ast
     * @return TypeHint
     */
    private function virtualTypeHint(EnumValueDefinition $value, TypeHintNode $ast): TypeHint
    {
        $virtual = new class($value->getDocument()) extends TypeHint {
            public static function getType(): TypeInterface
            {
                return Type::of(Type::ENUM_VALUE);
            }
        };

        $virtual->withOffset($ast->getOffset());
        $virtual->withTypeDefinition($ast->getTypeName());
        $virtual->withModifiers($ast->getModifiers());

        return $virtual;
    }
}
