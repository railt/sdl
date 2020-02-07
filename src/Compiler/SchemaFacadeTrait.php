<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Railt\TypeSystem\Schema;

/**
 * Trait SchemaFacadeTrait
 */
trait SchemaFacadeTrait
{
    /**
     * @var Schema
     */
    protected Schema $schema;

    /**
     * @return Schema
     */
    public function getSchema(): Schema
    {
        return $this->schema;
    }

    /**
     * @param Schema $schema
     * @return $this
     */
    public function setSchema(Schema $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @return void
     * @throws \Throwable
     */
    private function bootSchemaFacadeTrait(): void
    {
        $this->schema = new Schema();
    }
}
