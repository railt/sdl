<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend;

use Railt\SDL\Backend\Context\NamedDefinitionContextInterface;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Railt\TypeSystem\Schema;

/**
 * Class Context
 */
class Context
{
    /**
     * @var Schema
     */
    private Schema $schema;

    /**
     * @var array|NamedDefinitionContextInterface[]
     */
    private array $types = [];

    /**
     * @var array|NamedDefinitionContextInterface[]
     */
    private array $directives = [];

    /**
     * Context constructor.
     *
     * @param Schema $schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
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
     * @return Schema
     */
    public function getSchema(): Schema
    {
        return $this->schema;
    }

    /**
     * @param NamedDefinitionContextInterface $context
     * @return void
     */
    public function addTypeContext(NamedDefinitionContextInterface $context): void
    {
        if ($context->getGenericArguments() === []) {
            $this->schema->addType($context->resolve());

            return;
        }

        $this->types[$context->getName()] = $context;
    }

    /**
     * @param NamedDefinitionContextInterface $context
     * @return void
     */
    public function addDirectiveContext(NamedDefinitionContextInterface $context): void
    {
        if ($context->getGenericArguments() === []) {
            $this->schema->addDirective($context->resolve());

            return;
        }

        $this->directives[$context->getName()] = $context;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        return $this->schema->hasType($name)
            || isset($this->types[$name]);
    }

    /**
     * @param string $type
     * @return NamedDefinitionContextInterface|null
     */
    public function fetch(string $type): ?NamedDefinitionContextInterface
    {
        return $this->types[$type] ?? null;
    }
}
