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
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Type\ObjectType;

/**
 * Class ObjectTypeDefinitionContext
 */
class ObjectTypeDefinitionContext extends ObjectLikeTypeDefinitionContext
{
    /**
     * @param DefinitionNode|ObjectTypeDefinitionNode $ast
     * @return ObjectTypeInterface
     * @throws TypeUniquenessException
     * @throws \Throwable
     */
    public function build(DefinitionNode $ast): ObjectTypeInterface
    {
        $object = new ObjectType($ast->name->value, [
            'description' => $this->descriptionOf($ast),
        ]);

        foreach ($ast->interfaces as $impl) {
            $object->addInterface($this->ref($impl->interface));
        }

        foreach ($ast->fields as $field) {
            $object->addField($this->buildFieldDefinition($field));
        }

        return $object;
    }
}
