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
use Railt\Reflection\Definition\Dependent\FieldDefinition;
use Railt\SDL\Compiler\Ast\Dependent\FieldDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class FieldBuilder
 */
class FieldBuilder extends Builder
{
    /**
     * @param RuleInterface|FieldDefinitionNode $rule
     * @param Definition|Definition\TypeDefinition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $hint = $rule->getTypeHint();

        $field = new FieldDefinition($parent, $rule->getFieldName(), $hint->getTypeName());
        $field->withOffset($rule->getOffset());
        $field->withDescription($rule->getDescription());
        $field->withModifiers($hint->getModifiers());

        foreach ($rule->getArguments() as $ast) {
            $field->withArgument($this->dependent($ast, $field));
        }

        $this->when->runtime(function () use ($rule, $field) {
            foreach ($rule->getDirectives() as $ast) {
                $field->withDirective($this->dependent($ast, $field));
            }
        });

        return $field;
    }
}
