<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

/**
 * Class ValueObject
 */
class ValueObject implements
    \ArrayAccess,
    \Serializable,
    \JsonSerializable,
    DefinitionInterface
{
    /**
     * @var array|mixed[]
     */
    protected $attributes = [];

    /**
     * ValueObject constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($this->attributes as $key => $value) {
            $this->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value): void
    {
        $this->attributes[$name] = \is_array($value) ? new ValueObject($value) : $value;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $this->set($name, $value);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function has(string $name): bool
    {
        return isset($this->attributes[$name]) || \array_key_exists($name, $this->attributes);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function delete(string $name): bool
    {
        if (isset($this->$name)) {
            unset($this->attributes[$name]);

            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return $this->has($name);
    }

    /**
     * @param string $name
     * @return void
     */
    public function __unset(string $name): void
    {
        $this->delete($name);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return \serialize($this->attributes);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        $this->attributes = \unserialize($serialized, [
            'allowed_classes' => true,
        ]);
    }

    /**
     * @param string|int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->has((string)$offset);
    }

    /**
     * @param string|int $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get((string)$offset);
    }

    /**
     * @param string|int $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->set((string)$offset, $value);
    }

    /**
     * @param string|int $offset
     */
    public function offsetUnset($offset): void
    {
        $this->delete((string)$offset);
    }
}
