<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use Railt\SDL\Backend\NameResolver\NameResolverInterface;
use Railt\TypeSystem\Schema;

/**
 * Class GlobalContext
 */
class GlobalContext implements ContextInterface
{
    /**
     * @var array|LocalContextInterface[]
     */
    public array $types;

    /**
     * @var array|LocalContextInterface[]
     */
    public array $directives;

    /**
     * @var Schema
     */
    private Schema $schema;

    /**
     * @var NameResolverInterface
     */
    private NameResolverInterface $resolver;

    /**
     * GlobalContext constructor.
     *
     * @param NameResolverInterface $resolver
     * @param Schema $schema
     */
    public function __construct(NameResolverInterface $resolver, Schema $schema)
    {
        $this->schema = $schema;
        $this->resolver = $resolver;
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function setSchema(Schema $schema): void
    {
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
     * @param LocalContextInterface $context
     * @return void
     */
    public function addType(LocalContextInterface $context): void
    {
        if ($context->getGenericArguments() === []) {
            /** @var TypeInterface $type */
            $type = $context->build([]);

            $this->schema->addType($type);

            return;
        }

        $this->types[$context->getName()] = $context;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function hasType(string $type): bool
    {
        return isset($this->types[$type])
            || $this->schema->hasType($type);
    }

    /**
     * @param string $type
     * @param array|string[] $args
     * @return TypeInterface
     */
    public function fetchType(string $type, array $args = []): TypeInterface
    {
        if ($this->schema->hasType($type)) {
            return $this->schema->getType($type);
        }

        return $this->types[$type]->build($args);
    }

    /**
     * @param LocalContextInterface $context
     * @return void
     */
    public function addDirective(LocalContextInterface $context): void
    {
        if ($context->getGenericArguments() === []) {
            /** @var DirectiveInterface $directive */
            $directive = $context->build([]);

            $this->schema->addDirective($directive);

            return;
        }

        $this->directives[$context->getName()] = $context;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function hasDirective(string $type): bool
    {
        return isset($this->directives[$type])
            || $this->schema->getDirective($type);
    }

    /**
     * @param string $type
     * @param array $args
     * @return DirectiveInterface|NamedTypeInterface
     */
    public function fetchDirective(string $type, array $args = []): DirectiveInterface
    {
        if ($directive = $this->schema->getDirective($type)) {
            return $directive;
        }

        return $this->directives[$type]->build($args);
    }
}
