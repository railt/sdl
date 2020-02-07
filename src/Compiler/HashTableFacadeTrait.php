<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Backend\HashTable;
use Railt\SDL\Backend\HashTableInterface;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Trait HashTableFacadeTrait
 */
trait HashTableFacadeTrait
{
    use ValueFactoryFacadeTrait;

    /**
     * @var HashTableInterface
     */
    protected HashTableInterface $hash;

    /**
     * @return void
     */
    private function bootHashTableFacadeTrait(): void
    {
        $this->bootValueFactoryFacadeTrait();

        $this->hash = new HashTable($this->factory);
    }

    /**
     * @return HashTableInterface
     */
    public function getVariables(): HashTableInterface
    {
        return $this->hash;
    }

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function addVariable(string $name, $value): self
    {
        $this->hash->add($name, $value);

        return $this;
    }

    /**
     * @param string $name
     * @return ValueInterface|null
     */
    public function findVariable(string $name): ?ValueInterface
    {
        if ($this->hash->has($name)) {
            return $this->hash->get($name);
        }

        return null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable(string $name): bool
    {
        return $this->hash->has($name);
    }
}
