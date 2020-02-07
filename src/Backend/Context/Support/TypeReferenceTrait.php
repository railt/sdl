<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context\Support;

use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Backend\Context\Factory;
use Railt\SDL\Exception\TypeErrorException;
use Railt\SDL\Frontend\Ast\Type\ListTypeNode;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;
use Railt\SDL\Frontend\Ast\Type\NonNullTypeNode;
use Railt\SDL\Frontend\Ast\Type\TypeNode;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Railt\TypeSystem\Type\ListType;
use Railt\TypeSystem\Type\NonNullType;
use Railt\TypeSystem\Type\WrappingType;

/**
 * Trait TypeReferenceTrait
 */
trait TypeReferenceTrait
{
    /**
     * @param TypeNode $type
     * @param array $args
     * @return TypeReferenceInterface|WrappingType
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Throwable
     */
    protected function typeOf(TypeNode $type, array $args = [])
    {
        switch (true) {
            case $type instanceof NonNullTypeNode:
                return new NonNullType($this->typeOf($type->type, $args));

            case $type instanceof ListTypeNode:
                return new ListType($this->typeOf($type->type, $args));

            case $type instanceof NamedTypeNode:
                return $this->ref($type, $args);

            default:
                throw new \InvalidArgumentException('Invalid type ref');
        }
    }

    /**
     * @return array|string[]
     */
    abstract public function getGenericArguments(): array;

    /**
     * @param NamedTypeNode $node
     * @param array|string[] $args
     * @return TypeReferenceInterface
     * @throws \LogicException
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    protected function ref(NamedTypeNode $node, array $args = []): TypeReferenceInterface
    {
        $name = $node->name->value;
        $arguments = $this->refArguments($node, $args);

        $index = \array_search($name,$this->getGenericArguments(), true);

        if (\is_int($index)) {
            $name = $args[$index];
        }

        return $this->getFactory()->ref($node->name, $name, $arguments);
    }

    /**
     * @param NamedTypeNode $node
     * @param array $args
     * @return array
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    private function refArguments(NamedTypeNode $node, array $args): array
    {
        $arguments = [];

        foreach ($node->arguments as $argument) {
            $arguments[] = $this->ref($argument, $args)->getName();
        }

        return $arguments;
    }

    /**
     * @return Factory
     */
    abstract protected function getFactory(): Factory;
}
