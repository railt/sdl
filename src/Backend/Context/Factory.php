<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Backend\Context;
use Railt\SDL\Backend\HashTableInterface;
use Railt\SDL\Backend\NameResolver\NameResolverInterface;
use Railt\SDL\Exception\TypeErrorException;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\EnumTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InterfaceTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ScalarTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Reference\TypeReference;
use Railt\TypeSystem\Reference\TypeReferenceInterface;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @var string
     */
    private const ERROR_NON_GENERIC_TYPE_USAGE = 'Type %s can not be used as generic type %1$s<%s>';

    /**
     * @var string
     */
    private const ERROR_GENERIC_ARGUMENTS = 'Generic type %s<%s> requires %d type arguments, but %d passed';

    /**
     * @var Context
     */
    private Context $ctx;

    /**
     * @var NameResolverInterface
     */
    private NameResolverInterface $resolver;

    /**
     * @var HashTableInterface
     */
    private HashTableInterface $vars;

    /**
     * Factory constructor.
     *
     * @param HashTableInterface $vars
     * @param NameResolverInterface $resolver
     * @param Context $context
     */
    public function __construct(HashTableInterface $vars, NameResolverInterface $resolver, Context $context)
    {
        $this->vars = $vars;
        $this->ctx = $context;
        $this->resolver = $resolver;
    }

    /**
     * @param Node $from
     * @param string $name
     * @param array|string[] $args
     * @return TypeReferenceInterface
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    public function ref(Node $from, string $name, array $args = []): TypeReferenceInterface
    {
        if ($args !== []) {
            $name = $this->compileGenericType($from, $name, $args);
        } else {
            $this->assertMissingTypeArguments($from, $name, $args);
        }

        return new TypeReference($this->ctx->getSchema(), $name);
    }

    /**
     * @param Node $from
     * @param string $name
     * @param array $args
     * @return void
     */
    private function assertMissingTypeArguments(Node $from, string $name, array $args = []): void
    {
        $context = $this->ctx->fetch($name);

        if ($context === null) {
            return;
        }

        $needle = $context->getGenericArguments();

        if ($needle !== []) {
            $message = \vsprintf(self::ERROR_GENERIC_ARGUMENTS, [
                $name,
                \implode(', ', $needle),
                \count($needle),
                \count($args),
            ]);

            throw new TypeErrorException($message, $from);
        }
    }

    /**
     * @param Node $from
     * @param string $name
     * @param array|string[] $args
     * @return string
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    private function compileGenericType(Node $from, string $name, array $args): string
    {
        $generic = $this->resolver->resolve($name, $args);

        if (! $this->ctx->hasType($generic)) {
            $context = $this->getContext($from, $name, $args);

            $schema = $this->ctx->getSchema();

            $this->assertGenericArguments($from, $name, $context->getGenericArguments(), $args);

            $schema->addType($context->resolve($args)->withName($generic));
        }

        return $generic;
    }

    /**
     * @param Node $from
     * @param string $name
     * @param array|string[] $args
     * @return TypeDefinitionContextInterface
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    private function getContext(Node $from, string $name, array $args): TypeDefinitionContextInterface
    {
        $context = $this->ctx->fetch($name);

        if ($context === null) {
            $message = self::ERROR_NON_GENERIC_TYPE_USAGE;
            $message = \sprintf($message, $name, \implode(',', $args));

            throw new TypeErrorException($message, $from);
        }

        return $context;
    }

    /**
     * @param Node $from
     * @param string $name
     * @param array $definition
     * @param array $passed
     * @return void
     */
    private function assertGenericArguments(Node $from, string $name, array $definition, array $passed): void
    {
        [$definitions, $arguments] = [\count($definition), \count($passed)];

        if ($definitions !== $arguments) {
            $message = \vsprintf(self::ERROR_GENERIC_ARGUMENTS, [
                $name,
                \implode(', ', $definition),
                \count($definition),
                \count($passed),
            ]);

            throw new TypeErrorException($message, $from);
        }
    }

    /**
     * @param DefinitionNode $node
     * @return DefinitionContextInterface|TypeDefinitionContextInterface
     * @throws \LogicException
     */
    public function make(DefinitionNode $node): DefinitionContextInterface
    {
        switch (true) {
            case $node instanceof ObjectTypeDefinitionNode:
                return new ObjectTypeDefinitionContext($this->vars, $this, $node);
                break;

            case $node instanceof InterfaceTypeDefinitionNode:
                return new InterfaceTypeDefinitionContext($this->vars, $this, $node);
                break;

            case $node instanceof ScalarTypeDefinitionNode:
                return new ScalarTypeDefinitionContext($this->vars, $this, $node);
                break;

            case $node instanceof EnumTypeDefinitionNode:
            case $node instanceof InputObjectTypeDefinitionNode:
            case $node instanceof UnionTypeDefinitionNode:
            case $node instanceof DirectiveDefinitionNode:
            case $node instanceof SchemaDefinitionNode:
                throw new \LogicException($node->name->value . ' context not implemented yet');

                break;
        }

        throw new \LogicException(\get_class($node) . ' is an unresolvable');
    }
}
