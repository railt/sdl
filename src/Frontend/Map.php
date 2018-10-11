<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

/**
 * Class Map
 */
class Map implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @var array|object[]
     */
    private $keys = [];

    /**
     * @var array|object[]
     */
    private $values = [];

    /**
     * @var \Closure
     */
    private $toString;

    /**
     * Map constructor.
     * @param \Closure|null $hash
     */
    public function __construct(\Closure $hash = null)
    {
        $this->toString = $hash ?? [$this, 'hash'];
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return Map
     */
    public function set($key, $value): self
    {
        $id = $this->key($key);

        $this->keys[$id]   = $key;
        $this->values[$id] = $value;

        return $this;
    }

    /**
     * @param mixed $key
     * @param null $default
     * @return mixed|null|object
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->values[$this->key($key)];
        }

        return $default instanceof \Closure ? $default() : $default;
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public function has($key): bool
    {
        $id = $this->key($key);

        return isset($this->keys[$id]) || \array_key_exists($id, $this->keys);
    }

    /**
     * @param mixed $key
     * @return string
     */
    public function key($key): string
    {
        return ($this->toString)($key);
    }

    /**
     * @param mixed ...$keys
     */
    public function delete(...$keys): void
    {
        foreach ($keys as $key) {
            $id = $this->key($key);

            unset($this->keys[$id], $this->values[$id]);
        }
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function hash($value): string
    {
        switch (true) {
            case $value === null:
                return 'null';

            case \is_object($value):
                if (\method_exists($value, '__toString')) {
                    return 'o:' . $value;
                }

                return 'h:' . \spl_object_hash($value);

            case \is_numeric($value):
                return 'n:' . (int)$value;

            case \is_bool($value):
                return 'b:' . (int)$value;

            case \is_string($value):
                return 's:' . $value;

            case \is_resource($value):
                return 'r:' . $value;

            case \is_array($value):
                return 'a:' . \md5(\serialize($value));

            default:
                return 'v:' . \gettype($value) . ':' . \serialize($value);
        }
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->keys as $id => $key) {
            yield $key => $this->values[$id];
        }
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed|null|object
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        $this->delete($offset);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->keys);
    }
}
