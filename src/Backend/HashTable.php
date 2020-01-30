<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend;

use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Backend\HashTable\ValueFactory;
use Railt\SDL\Exception\RuntimeErrorException;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class HashTable
 */
final class HashTable implements HashTableInterface
{
    /**
     * @var array|ValueInterface[]
     */
    private array $variables = [];

    /**
     * @var ValueFactory
     */
    private ValueFactory $factory;

    /**
     * @var HashTableInterface|null
     */
    private ?HashTableInterface $parent;

    /**
     * HashTable constructor.
     *
     * @param ValueFactory $factory
     * @param array|ValueInterface[] $variables
     * @param HashTableInterface|null $parent
     */
    public function __construct(ValueFactory $factory, array $variables = [], HashTableInterface $parent = null)
    {
        $this->parent = $parent;
        $this->factory = $factory;

        $this->addMany($variables);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        if ($this->defined($name)) {
            return true;
        }

        if ($this->parent && $this->parent->has($name)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function defined(string $name): bool
    {
        return isset($this->variables[$name])
            || \array_key_exists($name, $this->variables);
    }

    /**
     * @param iterable $variables
     * @return $this
     */
    public function addMany(iterable $variables): self
    {
        foreach ($variables as $name => $value) {
            $this->add($name, $value);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function add(string $name, $value): self
    {
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param Node|null $ctx
     * @return ValueInterface
     * @throws NotAccessibleException
     * @throws RuntimeErrorException
     * @throws \RuntimeException
     */
    public function get(string $name, Node $ctx = null): ValueInterface
    {
        if ($this->defined($name)) {
            return $this->factory->make($this->variables[$name], $ctx);
        }

        if ($this->parent && $this->parent->has($name)) {
            return $this->parent->get($name, $ctx);
        }

        $error = \sprintf('Undefined variable $%s', $name);

        throw new RuntimeErrorException($error, $ctx);
    }
}
