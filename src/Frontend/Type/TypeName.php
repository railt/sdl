<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Type;

/**
 * Class TypeName
 */
class TypeName implements TypeNameInterface, \JsonSerializable
{
    /**
     * @var string
     */
    public const NAMESPACE_SEPARATOR = '/';

    /**
     * @var array|string[]
     */
    private $chunks;

    /**
     * @var string|null
     */
    private $fqn;

    /**
     * TypeName constructor.
     * @param array $chunks
     */
    public function __construct(array $chunks)
    {
        $this->chunks = $chunks;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->chunks);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->chunks[\count($this->chunks) - 1];
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
     * @param TypeNameInterface $prefix
     * @return TypeNameInterface
     */
    public function in(TypeNameInterface $prefix): TypeNameInterface
    {
        return new static(\array_merge($prefix->getChunks(), $this->getChunks()));
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
     * @param TypeNameInterface $name
     * @return bool
     */
    public function is(TypeNameInterface $name): bool
    {
        return $name->getFullyQualifiedName() === $this->getFullyQualifiedName();
    }

    /**
     * @param array $chunks
     * @return TypeName
     */
    public static function fromArray(array $chunks): TypeNameInterface
    {
        return new static($chunks);
    }

    /**
     * @return TypeNameInterface
     */
    public static function global(): TypeNameInterface
    {
        return new static([]);
    }

    /**
     * @param string $name
     * @return TypeNameInterface
     */
    public static function fromString(string $name): TypeNameInterface
    {
        $escaped = \ltrim($name, self::NAMESPACE_SEPARATOR);

        if (\strlen($escaped) === null) {
            return new Anonymous();
        }

        if ($name !== $escaped) {
            return new AtGlobal(\explode(self::NAMESPACE_SEPARATOR, $escaped));
        }

        return new static(\explode(self::NAMESPACE_SEPARATOR, $escaped));
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->getFullyQualifiedName();
    }
}
