<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type;

use function Railt\SDL\object_to_string;

/**
 * Class Name
 */
class Name implements TypeNameInterface, \JsonSerializable, \Countable
{
    /**
     * @var array|string[]
     */
    private $chunks;

    /**
     * @var int
     */
    private $size;

    /**
     * @var string|null
     */
    private $fqn;

    /**
     * @var bool
     */
    private $global;

    /**
     * TypeName constructor.
     * @param array $chunks
     * @param bool $global
     */
    public function __construct(array $chunks, bool $global = false)
    {
        $this->chunks = $chunks;
        $this->global = $global;
        $this->size   = \count($chunks);
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @param bool|null $global
     * @return TypeNameInterface
     */
    public static function new($name, bool $global = null): TypeNameInterface
    {
        switch (true) {
            case $name === null || ! $name:
                return static::empty((bool)$global);

            case $name instanceof TypeNameInterface:
                return $name;

            case \is_array($name):
                return static::fromArray($name, (bool)$global);

            case \is_iterable($name):
                return static::fromArray(\iterator_to_array($name, false), (bool)$global);

            case \is_scalar($name):
                return static::fromString((string)$name, $global);

            default:
                $error = 'Unsupported argument type of %s(self|array|iterable|scalar $name = %s)';
                $given = \is_object($name) ? object_to_string($name) : \gettype($name);

                throw new \InvalidArgumentException(\sprintf($error, __METHOD__, $given));
        }
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return bool
     */
    public static function isValid($name): bool
    {
        return $name === null || \is_scalar($name) || \is_iterable($name) || $name instanceof TypeNameInterface;
    }

    /**
     * @param array $chunks
     * @param bool $global
     * @return Name
     */
    public static function fromArray(array $chunks, bool $global = false): TypeNameInterface
    {
        return new static($chunks, $global);
    }

    /**
     * @param string $fqn
     * @param bool|null $global
     * @return TypeNameInterface
     */
    public static function fromString(string $fqn, bool $global = null): TypeNameInterface
    {
        $name   = \ltrim($fqn, self::NAMESPACE_SEPARATOR);
        $chunks = \explode(self::NAMESPACE_SEPARATOR, $name);

        return new static($chunks, \is_bool($global) ? $global : $name !== $fqn);
    }

    /**
     * @param bool $global
     * @return TypeNameInterface
     */
    public static function empty(bool $global = false): TypeNameInterface
    {
        return new static([], $global);
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->chunks);
    }

    /**
     * @return bool
     */
    public function isGlobal(): bool
    {
        return $this->global;
    }

    /**
     * @return TypeNameInterface
     */
    public function lock(): TypeNameInterface
    {
        $this->global = true;

        return $this;
    }

    /**
     * @return TypeNameInterface
     */
    public function unlock(): TypeNameInterface
    {
        $this->global = false;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->size > 0 ? $this->chunks[$this->size - 1] : '';
    }

    /**
     * @param string|iterable|TypeNameInterface|null $prefix
     * @return TypeNameInterface
     */
    public function in($prefix): TypeNameInterface
    {
        if ($this->global) {
            return clone $this;
        }

        return new static(\array_merge(self::new($prefix)->getChunks(), $this->getChunks()));
    }

    /**
     * @param string|iterable|TypeNameInterface|null $suffix
     * @return TypeNameInterface
     */
    public function append($suffix): TypeNameInterface
    {
        return self::new($suffix)->in($this);
    }

    /**
     * @return array|string[]
     */
    public function getChunks(): array
    {
        return $this->chunks;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getFullyQualifiedName();
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'name' => $this->getFullyQualifiedName(),
            'root' => $this->global,
        ];
    }

    /**
     * @return string
     */
    public function getFullyQualifiedName(): string
    {
        if ($this->fqn === null) {
            $this->fqn = \implode(static::NAMESPACE_SEPARATOR, $this->chunks);
        }

        return $this->fqn;
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return bool
     */
    public function is($name): bool
    {
        return self::new($name)->getFullyQualifiedName() === $this->getFullyQualifiedName();
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->getFullyQualifiedName();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->size;
    }
}
