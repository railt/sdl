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
use Railt\SDL\Backend\Context\Factory;
use Railt\SDL\Backend\NameResolver\NameResolverInterface;
use Railt\SDL\Frontend\Ast\Definition\Type\TypeDefinitionNode;

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
     * @param HashTableInterface $vars
     * @param NameResolverInterface $resolver
     * @param Context $context
     */
    public function __construct(HashTableInterface $vars, NameResolverInterface $resolver, Context $context)
    {
        $this->context = $context;

        $this->factory = new Factory($vars, $resolver, $context);
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
                $this->context->addTypeContext($this->factory->make($node));
                break;
        }
    }
}
