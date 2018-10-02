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
     * @var null|TypeInterface
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
        $this->type = $type;
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
     * @throws TypeConflictException
     */
    public function set(?ValueInterface $value): VarSymbolInterface
    {
        $allowed =
            // Not initialized variable or removing value
            $value === null ||
            $this->type === null ||
            // Or same type
            $value->getType()->typeOf($this->type);

        if ($allowed) {
            $this->value = $value;

            return $this;
        }

        $error = 'Can not set a new value of type %s into %s $%s';
        throw new TypeConflictException(\sprintf($error, $value->getType(), $this->type, $this->getName()));
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
    public function getType(): ?TypeInterface
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return \sprintf('<%s:%s> = %s', $this->getName(), $this->getType() ?? '?', $this->value ?? 'Null');
    }
}

