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
class ValueObject implements \ArrayAccess, \JsonSerializable
{
    /**
     * @var int
     */
    public const KEEP_ALL = 0x00;

    /**
     * @var int
     */
    public const SKIP_NULL = 0x01;

    /**
     * @var int
     */
    public const SKIP_EMPTY = 0x02;

    /**
     * All of the attributes set on the container.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * @var bool
     */
    protected $skip = self::KEEP_ALL;

    /**
     * Create a new ValueObject container instance.
     *
     * @param iterable $attributes
     * @return void
     */
    public function __construct(iterable $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * Get an attribute from the container.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (\array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return \is_callable($default) ? $default() : $default;
    }

    /**
     * Set an attribute to the container.
     *
     * @param string|int $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value): self
    {
        $skipNullable = $this->skip === static::SKIP_NULL && $value === null;

        $skipEmpty = $this->skip === static::SKIP_EMPTY && ! $value;

        if (! $skipEmpty && ! $skipNullable) {
            $this->attributes[$key ?? \count($this->attributes)] =
                \is_iterable($value) ? new self($value) : $value;
        }

        return $this;
    }

    /**
     * Set the attributes to the container.
     *
     * @param iterable $attributes
     * @return $this
     */
    public function setAttributes(iterable $attributes = []): self
    {
        foreach ($attributes as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Get the attributes from the container.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Convert the ValueObject instance to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $applicator = function ($value) {
            if (\is_object($value) && \method_exists($value, '__toString')) {
                return (string)$value;
            }

            return $value;
        };

        return \array_map($applicator, $this->toArray());
    }

    /**
     * Convert the ValueObject instance to JSON.
     *
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return (string)\json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Determine if the given offset exists.
     *
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param string|int $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Set the value at the given offset.
     *
     * @param string|int $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * Unset the value at the given offset.
     *
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Handle dynamic calls to the container to set attributes.
     *
     * @param string $method
     * @param array $parameters
     * @return $this
     */
    public function __call(string $method, array $parameters = []): self
    {
        $this->attributes[$method] = \count($parameters) > 0 ? $parameters[0] : true;

        return $this;
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set(string $key, $value): void
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Dynamically unset an attribute.
     *
     * @param string $key
     * @return void
     */
    public function __unset(string $key): void
    {
        $this->offsetUnset($key);
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->attributes;
    }
}
