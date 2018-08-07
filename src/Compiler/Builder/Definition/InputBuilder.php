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
use Railt\Reflection\Definition\InputDefinition;
use Railt\SDL\Compiler\Ast\Definition\InputDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class InputBuilder
 */
class InputBuilder extends Builder
{
    /**
     * @param RuleInterface|InputDefinitionNode $rule
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $input = new InputDefinition($parent->getDocument(), $rule->getTypeName());
        $input->withOffset($rule->getOffset());
        $input->withDescription($rule->getDescription());

        $this->when->runtime(function () use ($rule, $input): void {
            foreach ($rule->getDirectives() as $ast) {
                $input->withDirective($this->dependent($ast, $input));
            }
        });

        foreach ($rule->getInputFields() as $ast) {
            $input->withField($this->dependent($ast, $input));
        }

        return $input;
    }
}
