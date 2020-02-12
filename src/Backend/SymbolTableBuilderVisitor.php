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
use Railt\SDL\Backend\Context\DirectiveDefinitionLocator;
use Railt\SDL\Backend\Context\EnumTypeDefinitionLocator;
use Railt\SDL\Backend\Context\InputObjectTypeDefinitionLocator;
use Railt\SDL\Backend\Context\InterfaceTypeDefinitionLocator;
use Railt\SDL\Backend\Context\ObjectTypeDefinitionLocator;
use Railt\SDL\Backend\Context\ScalarTypeDefinitionLocator;
use Railt\SDL\Backend\Context\TypeLocatorInterface;
use Railt\SDL\Backend\Context\UnionTypeDefinitionLocator;
use Railt\SDL\Exception\TypeErrorException;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\EnumTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InterfaceTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ScalarTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\TypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;

/**
 * Class TypeBuilderVisitor
 */
class SymbolTableBuilderVisitor extends Visitor
{
    /**
     * @var string
     */
    private const ERROR_TYPE_DUPLICATION = 'GraphQL %s type has already been defined';

    /**
     * @var string
     */
    private const ERROR_DIRECTIVE_DUPLICATION = 'GraphQL directive @%s has already been defined';

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
                $this->registerDirectiveOrError(new DirectiveDefinitionLocator($node), $node);

                break;

            case $node instanceof EnumTypeDefinitionNode:
                $this->registerTypeOrError(new EnumTypeDefinitionLocator($node), $node);

                break;

            case $node instanceof InterfaceTypeDefinitionNode:
                $this->registerTypeOrError(new InterfaceTypeDefinitionLocator($node), $node);

                break;

            case $node instanceof InputObjectTypeDefinitionNode:
                $this->registerTypeOrError(new InputObjectTypeDefinitionLocator($node), $node);

                break;

            case $node instanceof ObjectTypeDefinitionNode:
                $this->registerTypeOrError(new ObjectTypeDefinitionLocator($node), $node);

                break;

            case $node instanceof ScalarTypeDefinitionNode:
                $this->registerTypeOrError(new ScalarTypeDefinitionLocator($node), $node);

                break;

            case $node instanceof UnionTypeDefinitionNode:
                $this->registerTypeOrError(new UnionTypeDefinitionLocator($node), $node);

                break;
        }
    }

    /**
     * @param TypeLocatorInterface $ctx
     * @param DirectiveDefinitionNode $node
     * @return void
     */
    private function registerDirectiveOrError(TypeLocatorInterface $ctx, DirectiveDefinitionNode $node): void
    {
        if ($this->context->hasDirective($ctx->getName())) {
            $message = \sprintf(self::ERROR_DIRECTIVE_DUPLICATION, $ctx->getName());

            throw TypeErrorException::fromAst($message, $node->name);
        }

        $this->context->addDirective($ctx);
    }

    /**
     * @param TypeLocatorInterface $context
     * @param TypeDefinitionNode $node
     * @return void
     */
    private function registerTypeOrError(TypeLocatorInterface $context, TypeDefinitionNode $node): void
    {
        if ($this->context->hasType($context->getName())) {
            $message = \sprintf(self::ERROR_TYPE_DUPLICATION, $context->getName());

            throw TypeErrorException::fromAst($message, $node->name);
        }

        $this->context->addType($context);
    }
}
