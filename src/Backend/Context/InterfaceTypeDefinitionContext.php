<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use Railt\SDL\Frontend\Ast\Definition\Type\InterfaceTypeDefinitionNode;
use Railt\TypeSystem\Type\InterfaceType;

/**
 * @property-read InterfaceTypeDefinitionNode $ast
 */
class InterfaceTypeDefinitionContext extends ObjectLikeTypeDefinitionContext
{
    /**
     * @param array $args
     * @return InterfaceTypeInterface
     * @throws \Throwable
     */
    public function resolve(array $args = []): InterfaceTypeInterface
    {
        $interface = new InterfaceType($this->ast->name->value, [
            'description' => $this->descriptionOf($this->ast),
        ]);

        foreach ($this->ast->interfaces as $impl) {
            $interface->addInterface($this->ref($impl->interface, $args));
        }

        foreach ($this->ast->fields as $field) {
            $interface->addField($this->buildFieldDefinition($field, $args));
        }

        return $interface;
    }
}
