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
use Railt\Reflection\Type;
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
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $hint = $rule->getTypeHint();

        $argument = new ArgumentDefinition($parent, $rule->getArgumentName(), $hint->getTypeName());
        $argument->withOffset($rule->getOffset());
        $argument->withDescription($rule->getDescription());
        $argument->withModifiers($hint->getModifiers());

        $this->when->resolving(function() use ($argument) {
            $this->shouldBeTypeOf($argument, $argument->getDefinition(), [
                Type::SCALAR,
                Type::ENUM,
                Type::INPUT_OBJECT,
                Type::ANY,
            ]);
        });

        $this->when->runtime(function () use ($rule, $argument): void {
            if ($default = $rule->getDefaultValue()) {
                $argument->withDefaultValue($this->valueOf($argument, $default));
            } elseif (! $argument->isNonNull()) {
                $argument->withDefaultValue(null);
            }

            foreach ($rule->getDirectives() as $ast) {
                $argument->withDirective($this->dependent($ast, $argument));
            }
        });

        return $argument;
    }
}
