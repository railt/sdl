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
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $value = new EnumValueDefinition($parent, $rule->getName());
        $value->withOffset($rule->getOffset());
        $value->withDescription($rule->getDescription());

        if ($hint = $rule->getTypeHint()) {
            // TODO With Value
        }

        foreach ($rule->getDirectives() as $ast) {
            $value->withDirective($this->dependent($ast, $value));
        }

        return $value;
    }
}
