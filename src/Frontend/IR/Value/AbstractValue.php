<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Value;

use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\TypeInterface;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class AbstractValue
 */
abstract class AbstractValue implements ValueInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var TypeDefinition|null
     */
    private $type;

    /**
     * @var int
     */
    private $offset;

    /**
     * AbstractValue constructor.
     * @param mixed $value
     * @param int $offset
     */
    public function __construct($value, int $offset = 0)
    {
        $this->value = $value;
        $this->offset = $offset;
    }

    /**
     * @return TypeDefinition|null
     */
    public function getTypeDefinition(): ?TypeDefinition
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return ValueInterface
     */
    public function setOffset(int $offset): ValueInterface
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @param TypeDefinition $type
     * @return ValueInterface
     * @throws TypeConflictException
     */
    public function bindTo(TypeDefinition $type): ValueInterface
    {
        if ($this->typeOf($type::getType())) {
            $this->type = $type;

            return $this;
        }

        throw new TypeConflictException(\sprintf('Could not cast %s to %s value', $this->type, $type));
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function typeOf(TypeInterface $type): bool
    {
        return $this->type::typeOf($type);
    }

    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function instanceOf(TypeDefinition $type): bool
    {
        return $this->type->instanceOf($type);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    abstract public function toString(): string;

    /**
     * @return TypeDefinition
     */
    public function getType(): TypeDefinition
    {
        return $this->type;
    }
}
