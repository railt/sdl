<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\SymbolTable;

use Railt\SDL\Exception\TypeConflictException;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\Type\TypeInterface;

/**
 * Class Record
 */
class VarSymbol implements VarSymbolInterface
{
    /**
     * @var bool
     */
    protected $const = true;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * @var ValueInterface|null
     */
    private $value;

    /**
     * Record constructor.
     * @param string $name
     * @param TypeInterface|null $type
     */
    public function __construct(string $name, TypeInterface $type = null)
    {
        $this->name = $name;
        $this->type = $type ?? Type::any();
    }

    /**
     * @return null|ValueInterface
     */
    public function getValue(): ?ValueInterface
    {
        return $this->value;
    }

    /**
     * @param null|ValueInterface $value
     * @return VarSymbolInterface
     */
    private function setValue(?ValueInterface $value): VarSymbolInterface
    {
        $this->value = $value;

        if ($value) {
            $this->type = $value->getType();
        }

        return $this;
    }

    /**
     * @param null|ValueInterface $value
     * @return VarSymbolInterface
     * @throws TypeConflictException
     */
    public function set(?ValueInterface $value): VarSymbolInterface
    {
        if ($this->value === null) {
            return $this->setValue($value);
        }

        if ($this->isConstant()) {
            $error = 'Can not redefine a %s';
            throw new TypeConflictException(\sprintf($error, $this));
        }

        if ($value === null) {
            return $this->setValue($value);
        }

        if ($this->isSameType($value)) {
            $this->type  = $value->getType();
            $this->value = $value;

            return $this;
        }

        $error = 'Can not set a new value of type %s into %s';
        throw new TypeConflictException(\sprintf($error, $value->getType(), $this));
    }

    /**
     * @param ValueInterface $value
     * @return bool
     */
    private function isSameType(ValueInterface $value): bool
    {
        return $value->getType()->typeOf($this->type);
    }

    /**
     * @return VarSymbolInterface
     */
    public function lock(): VarSymbolInterface
    {
        $this->const = true;

        return $this;
    }

    /**
     * @return VarSymbolInterface
     */
    public function unlock(): VarSymbolInterface
    {
        $this->const = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function isConstant(): bool
    {
        return $this->const;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $type   = $this->getType() ?? '?';
        $value  = $this->value ?? 'Null';
        $prefix = $this->isConstant() ? 'const' : 'var';

        return \sprintf('%s %s: $%s = %s', $prefix, $type, $this->getName(), $value);
    }
}
