<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\TypeSystem\Schema;

/**
 * Class Context
 */
abstract class Context implements ContextInterface
{
    /**
     * @var Schema
     */
    protected Schema $schema;

    /**
     * @var array|LocalContextInterface[]
     */
    protected array $contexts = [];

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
     * @param LocalContextInterface $context
     * @return void
     */
    public function add(LocalContextInterface $context): void
    {
        $this->contexts[$context->getName()] = $context;
    }
}
