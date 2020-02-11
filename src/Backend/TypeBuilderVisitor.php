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
use Railt\SDL\Backend\Context\ContextInterface;
use Railt\SDL\Backend\Context\DirectiveDefinitionContext;
use Railt\SDL\Backend\Context\EnumTypeDefinitionContext;
use Railt\SDL\Backend\Context\InputObjectTypeDefinitionContext;
use Railt\SDL\Backend\Context\InterfaceTypeDefinitionContext;
use Railt\SDL\Backend\Context\ObjectTypeDefinitionContext;
use Railt\SDL\Backend\Context\ScalarTypeDefinitionContext;
use Railt\SDL\Backend\Context\UnionTypeDefinitionContext;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\EnumTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InterfaceTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ScalarTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;

/**
 * Class TypeBuilderVisitor
 */
class TypeBuilderVisitor extends Visitor
{
    /**
     * @var ContextInterface
     */
    private ContextInterface $context;

    /**
     * TypeBuilderVisitor constructor.
     *
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @param NodeInterface $node
     * @return void
     * @throws \Throwable
     */
    public function enter(NodeInterface $node): void
    {
        switch (true) {
            case $node instanceof DirectiveDefinitionNode:
                $this->context->addDirective(new DirectiveDefinitionContext($node));

                break;

            case $node instanceof EnumTypeDefinitionNode:
                $this->context->addType(new EnumTypeDefinitionContext($node));

                break;

            case $node instanceof InterfaceTypeDefinitionNode:
                $this->context->addType(new InterfaceTypeDefinitionContext($node));

                break;

            case $node instanceof InputObjectTypeDefinitionNode:
                $this->context->addType(new InputObjectTypeDefinitionContext($node));

                break;

            case $node instanceof ObjectTypeDefinitionNode:
                $this->context->addType(new ObjectTypeDefinitionContext($node));

                break;

            case $node instanceof ScalarTypeDefinitionNode:
                $this->context->addType(new ScalarTypeDefinitionContext($node));

                break;

            case $node instanceof UnionTypeDefinitionNode:
                $this->context->addType(new UnionTypeDefinitionContext($node));

                break;
        }
    }
}
