<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Backend\HashTable\ValueFactory;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Trait ValueFactoryFacadeTrait
 */
trait ValueFactoryFacadeTrait
{
    /**
     * @var ValueFactory
     */
    protected ValueFactory $factory;

    /**
     * @return void
     */
    private function bootValueFactoryFacadeTrait(): void
    {
        $this->factory = new ValueFactory();
    }

    /**
     * @return ValueFactory
     */
    public function getValueFactory(): ValueFactory
    {
        return $this->factory;
    }

    /**
     * @param \Closure $caster
     * @param bool $append
     * @return $this
     */
    public function addValueCaster(\Closure $caster, bool $append = true): self
    {
        $this->factory->addCaster($caster, $append);

        return $this;
    }

    /**
     * @param mixed $value
     * @return ValueInterface
     */
    public function cast($value): ValueInterface
    {
        return $this->factory->make($value);
    }
}
