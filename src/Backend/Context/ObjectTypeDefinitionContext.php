<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Exception\TypeErrorException;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Type\ObjectType;

/**
 * @property-read ObjectTypeDefinitionNode $ast
 */
class ObjectTypeDefinitionContext extends ObjectLikeTypeDefinitionContext
{
    /**
     * @param array $args
     * @return ObjectTypeInterface
     * @throws \Throwable
     */
    public function resolve(array $args = []): ObjectTypeInterface
    {
        $object = new ObjectType($this->ast->name->value, [
            'description' => $this->descriptionOf($this->ast),
        ]);

        foreach ($this->ast->interfaces as $impl) {
            $object->addInterface($this->ref($impl->interface, $args));
        }

        foreach ($this->ast->fields as $field) {
            $object->addField($this->buildFieldDefinition($field, $args));
        }

        return $object;
    }
}
