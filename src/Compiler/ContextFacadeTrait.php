<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Backend\Context\GlobalContext;
use Railt\TypeSystem\Schema;

/**
 * Trait ContextFacadeTrait
 */
trait ContextFacadeTrait
{
    use NameResolverFacadeTrait;
    use SchemaFacadeTrait {
        setSchema as private _setSchema;
    }

    /**
     * @var GlobalContext
     */
    protected GlobalContext $context;

    /**
     * @param Schema $schema
     * @return $this
     */
    public function setSchema(Schema $schema): self
    {
        $this->_setSchema($schema);

        $this->context->setSchema($schema);

        return $this;
    }

    /**
     * @return void
     * @throws \Throwable
     */
    private function bootContextFacadeTrait(): void
    {
        $this->bootSchemaFacadeTrait();
        $this->bootNameResolverFacadeTrait();

        $this->context = new GlobalContext($this->getNameResolver(), $this->getSchema());
    }
}
