<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use Railt\SDL\Frontend\Ast\Definition\ArgumentDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\FieldDefinitionNode;
use Railt\TypeSystem\Argument;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Field;

/**
 * Class ObjectLikeTypeDefinitionContext
 */
abstract class ObjectLikeTypeDefinitionContext extends NamedDefinitionContext
{
    /**
     * @param FieldDefinitionNode $node
     * @param array $args
     * @return FieldInterface
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws TypeUniquenessException
     * @throws \Throwable
     */
    protected function buildFieldDefinition(FieldDefinitionNode $node, array $args = []): FieldInterface
    {
        $field = new Field($node->name->value, $this->typeOf($node->type, $args), [
            'description' => $this->descriptionOf($node),
        ]);

        foreach ($node->arguments as $argument) {
            $field->addArgument($this->buildArgumentDefinition($argument, $args));
        }

        foreach ($node->directives as $directive) {
            // TODO Directive
        }

        return $field;
    }

    /**
     * @param ArgumentDefinitionNode $node
     * @param array $args
     * @return ArgumentInterface
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Throwable
     */
    protected function buildArgumentDefinition(ArgumentDefinitionNode $node, array $args = []): ArgumentInterface
    {
        $argument = new Argument($node->name->value, $this->typeOf($node->type, $args), [
            'description' => $this->descriptionOf($node),
        ]);

        if ($node->defaultValue) {
            $argument->setDefaultValue($this->value($node->defaultValue));
        }

        foreach ($node->directives as $directive) {
            // TODO Directive
        }

        return $argument;
    }
}
