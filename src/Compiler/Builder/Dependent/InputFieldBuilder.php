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
use Railt\Reflection\Definition\Dependent\InputFieldDefinition;
use Railt\SDL\Compiler\Ast\Dependent\InputFieldDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class InputFieldBuilder
 */
class InputFieldBuilder extends Builder
{
    /**
     * @param RuleInterface|InputFieldDefinitionNode $rule
     * @param Definition|Definition\TypeDefinition $parent
     * @return Definition
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $hint = $rule->getTypeHint();

        $field = new InputFieldDefinition($parent, $rule->getFieldName(), $hint->getTypeName());
        $field->withOffset($rule->getOffset());
        $field->withDescription($rule->getDescription());
        $field->withModifiers($hint->getModifiers());

        $this->when->runtime(function () use ($rule, $field): void {
            if ($default = $rule->getDefaultValue()) {
                $field->withDefaultValue($this->valueOf($field, $default));
            }

            foreach ($rule->getDirectives() as $ast) {
                $field->withDirective($this->dependent($ast, $field));
            }
        });

        return $field;
    }
}
