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
use Railt\Reflection\Definition\Dependent\ArgumentDefinition;
use Railt\SDL\Compiler\Ast\Dependent\ArgumentDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class ArgumentBuilder
 */
class ArgumentBuilder extends Builder
{
    /**
     * @param RuleInterface|ArgumentDefinitionNode $rule
     * @param Definition|Definition\TypeDefinition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $hint = $rule->getTypeHint();

        $argument = new ArgumentDefinition($parent, $rule->getArgumentName(), $hint->getTypeName());
        $argument->withOffset($rule->getOffset());
        $argument->withDescription($rule->getDescription());
        $argument->withModifiers($hint->getModifiers());

        if ($default = $rule->getDefaultValue()) {
            // TODO Default value
        }

        foreach ($rule->getDirectives() as $ast) {
            $argument->withDirective($this->dependent($ast, $argument));
        }

        return $argument;
    }
}
