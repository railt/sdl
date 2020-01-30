<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Railt\SDL\Backend\Context;
use Railt\SDL\Backend\HashTable;
use Railt\SDL\Backend\NameResolver\NameResolverInterface;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\EnumTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InterfaceTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ScalarTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\TypeSystem\Reference\TypeReference;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Railt\TypeSystem\Schema;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @var Context
     */
    private Context $ctx;

    /**
     * @var Schema
     */
    private Schema $schema;

    /**
     * @var NameResolverInterface
     */
    private NameResolverInterface $resolver;

    /**
     * Factory constructor.
     *
     * @param NameResolverInterface $resolver
     * @param Context $context
     * @param Schema $schema
     */
    public function __construct(NameResolverInterface $resolver, Context $context, Schema $schema)
    {
        $this->resolver = $resolver;
        $this->ctx = $context;
        $this->schema = $schema;
    }

    /**
     * @return SchemaInterface
     */
    public function getSchema(): SchemaInterface
    {
        return $this->schema;
    }

    /**
     * @param string $name
     * @param array|string[] $args
     * @return TypeReferenceInterface
     */
    public function ref(string $name, array $args = []): TypeReferenceInterface
    {
        if ($args !== []) {
            $name = $this->generic($name, $args);
        }

        return new TypeReference($this->schema, $name);
    }

    /**
     * @param string $name
     * @param array|string[] $args
     * @return string
     */
    private function generic(string $name, array $args): string
    {
        $generic = $this->resolver->resolve($name, $args);

        if (! $this->ctx->hasType($generic)) {
            // TODO Register in context
        }

        return $generic;
    }

    /**
     * @param DefinitionNode $node
     * @return DefinitionContextInterface
     * @throws \LogicException
     */
    public function make(DefinitionNode $node): DefinitionContextInterface
    {
        switch (true) {
            case $node instanceof ObjectTypeDefinitionNode:
                return new ObjectTypeDefinitionContext($this, $node);
                break;

            case $node instanceof ScalarTypeDefinitionNode:
                return new ScalarTypeDefinitionContext($this, $node);
                break;

            case $node instanceof EnumTypeDefinitionNode:
            case $node instanceof InputObjectTypeDefinitionNode:
            case $node instanceof InterfaceTypeDefinitionNode:
            case $node instanceof UnionTypeDefinitionNode:
            case $node instanceof DirectiveDefinitionNode:
            case $node instanceof SchemaDefinitionNode:
                throw new \LogicException($node->name->value . ' context not implemented yet');

                break;
        }

        throw new \LogicException(\get_class($node) . ' is an unresolvable');
    }
}
