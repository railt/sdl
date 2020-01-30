<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Backend\HashTable;
use Railt\SDL\Backend\HashTable\VariablesVisitor;
use Railt\SDL\Backend\HashTableInterface;
use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\ListTypeNode;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;
use Railt\SDL\Frontend\Ast\Type\NonNullTypeNode;
use Railt\SDL\Frontend\Ast\Type\TypeNode;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Railt\TypeSystem\Type\ListType;
use Railt\TypeSystem\Type\NonNullType;
use Railt\TypeSystem\Type\WrappingType;
use Railt\TypeSystem\Value\StringValue;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class DefinitionContext
 */
abstract class DefinitionContext implements DefinitionContextInterface
{
    /**
     * @var DefinitionNode
     */
    protected DefinitionNode $ast;

    /**
     * @var Factory
     */
    private Factory $factory;

    /**
     * DefinitionContext constructor.
     *
     * @param Factory $factory
     * @param DefinitionNode $ast
     */
    public function __construct(Factory $factory, DefinitionNode $ast)
    {
        $this->factory = $factory;
        $this->ast = $ast;
    }

    /**
     * @param HashTableInterface $vars
     * @return DefinitionInterface
     */
    final public function resolve(HashTableInterface $vars): DefinitionInterface
    {
        $traverser = new Traverser([
            new VariablesVisitor($vars),
        ]);

        /** @var DefinitionNode $ast */
        $ast = $traverser->traverse($this->ast);

        return $this->build($ast);
    }

    /**
     * @param DefinitionNode $ast
     * @return DefinitionInterface
     */
    abstract protected function build(DefinitionNode $ast): DefinitionInterface;

    /**
     * @param DefinitionNode $ast
     * @param HashTable $table
     * @return DefinitionInterface
     * @throws \LogicException
     */
    protected function make(DefinitionNode $ast, HashTable $table): DefinitionInterface
    {
        return $this->factory->make($ast)->resolve($table);
    }

    /**
     * @param Node $node
     * @return string|null
     */
    protected function descriptionOf(Node $node): ?string
    {
        return $node->description instanceof StringValue
            ? $node->description->toPHPValue()
            : null;
    }

    /**
     * @param ValueInterface $value
     * @return ValueInterface
     */
    protected function value(ValueInterface $value): ValueInterface
    {
        return $value;
    }

    /**
     * @param TypeNode $type
     * @return TypeReferenceInterface|WrappingType
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    protected function typeOf(TypeNode $type)
    {
        switch (true) {
            case $type instanceof NonNullTypeNode:
                return new NonNullType($this->typeOf($type->type));

            case $type instanceof ListTypeNode:
                return new ListType($this->typeOf($type->type));

            case $type instanceof NamedTypeNode:
                return $this->ref($type);

            default:
                throw new \InvalidArgumentException('Invalid type ref');
        }
    }

    /**
     * @param NamedTypeNode $node
     * @return TypeReferenceInterface
     */
    protected function ref(NamedTypeNode $node): TypeReferenceInterface
    {
        return $this->factory->ref($node->name->value);
    }
}
