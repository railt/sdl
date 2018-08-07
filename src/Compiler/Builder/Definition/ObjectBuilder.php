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
use Railt\Reflection\Definition\ObjectDefinition;
use Railt\SDL\Compiler\Ast\Definition\ObjectDefinitionNode;
use Railt\SDL\Compiler\Builder\Builder;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder extends Builder
{
    /**
     * @param RuleInterface|ObjectDefinitionNode $rule
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $object = new ObjectDefinition($parent->getDocument(), $rule->getTypeName());

        $object->withOffset($rule->getOffset());
        $object->withDescription($rule->getDescription());

        foreach ($rule->getFields() as $ast) {
            $object->withField($this->dependent($ast, $object));
        }

        $this->when->resolving(function () use ($rule, $object): void {
            foreach ($rule->getImplementations() as $interface) {
                $object->withInterface($interface->getTypeName());
            }
        });

        $this->when->runtime(function () use ($rule, $object): void {
            foreach ($rule->getDirectives() as $ast) {
                $object->withDirective($this->dependent($ast, $object));
            }
        });

        return $object;
    }
}
