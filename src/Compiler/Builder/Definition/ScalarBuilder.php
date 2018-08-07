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
use Railt\Reflection\Definition\ScalarDefinition;
use Railt\SDL\Compiler\Ast\Definition\ScalarDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class ScalarBuilder
 */
class ScalarBuilder extends Builder
{
    /**
     * @param RuleInterface|ScalarDefinitionNode $rule
     * @param Definition $parent
     * @return Definition
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $scalar = new ScalarDefinition($parent->getDocument(), $rule->getTypeName());
        $scalar->withOffset($rule->getOffset());
        $scalar->withDescription($rule->getDescription());

        $this->when->resolving(function () use ($rule, $scalar): void {
            if ($ast = $rule->getExtends()) {
                $parent = $this->load($ast->getTypeName(), $scalar);

                if (! ($parent instanceof Definition\ScalarDefinition)) {
                    $error = '%s can extends only Scalar type, but %s given';
                    throw (new TypeConflictException(\sprintf($error, $scalar, $parent)))
                        ->throwsIn($scalar->getFile(), $rule->getOffset());
                }

                $scalar->extends($parent);
            }
        });

        $this->when->runtime(function () use ($rule, $scalar): void {
            foreach ($rule->getDirectives() as $ast) {
                $scalar->withDirective($this->dependent($ast, $scalar));
            }
        });

        return $scalar;
    }
}
