<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend;

use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Visitor\Visitor;
use Railt\SDL\Backend\Context\DirectiveContext;
use Railt\SDL\Backend\Context\EnumTypeDefinitionContext;
use Railt\SDL\Backend\Context\Factory;
use Railt\SDL\Backend\Context\InputObjectTypeDefinitionContext;
use Railt\SDL\Backend\Context\InterfaceTypeDefinitionContext;
use Railt\SDL\Backend\Context\ObjectTypeDefinitionContext;
use Railt\SDL\Backend\Context\ScalarTypeDefinitionContext;
use Railt\SDL\Backend\Context\UnionTypeDefinitionContext;
use Railt\SDL\Backend\NameResolver\NameResolverInterface;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\EnumTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InterfaceTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ScalarTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\TypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;
use Railt\TypeSystem\Reference\TypeReference;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Railt\TypeSystem\Schema;

/**
 * Class TypeBuilderVisitor
 */
class TypeBuilderVisitor extends Visitor
{
    /**
     * @var Factory
     */
    private Factory $factory;

    /**
     * @var Context
     */
    private Context $context;

    /**
     * TypeBuilderVisitor constructor.
     *
     * @param NameResolverInterface $resolver
     * @param Context $context
     * @param Schema $schema
     */
    public function __construct(NameResolverInterface $resolver, Context $context, Schema $schema)
    {
        $this->context = $context;

        $this->factory = new Factory($resolver, $context, $schema);
    }

    /**
     * @param NodeInterface $node
     * @return void
     * @throws \Throwable
     */
    public function leave(NodeInterface $node): void
    {
        switch (true) {
            case $node instanceof TypeDefinitionNode:
                $this->context->addType($this->factory->make($node));
                break;
        }
    }
}
