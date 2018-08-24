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
     * @var TypeDefinition
     */
    private $type;

    /**
     * @var string|null
     */
    private $representation;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * AbstractValue constructor.
     * @param $value
     * @param TypeDefinition $type
     */
    public function __construct($value, TypeDefinition $type)
    {
        $this->value = $value;
        $this->type  = $type;
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
     * @param string $value
     * @return ValueInterface
     */
    public function setRepresentation(string $value): ValueInterface
    {
        $this->representation = $value;

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
        if ($this->representation === null) {
            return '? of ' . $this->getType();
        }

        return $this->representation;
    }

    /**
     * @return TypeDefinition
     */
    public function getType(): TypeDefinition
    {
        return $this->type;
    }
}
