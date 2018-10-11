<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\SymbolTable;

use Railt\SDL\IR\Type\TypeInterface;

/**
 * Class Value
 */
class Value implements ValueInterface
{
    /**
     * @var TypeInterface|null
     */
    private $type;

    /**
     * @var int|string|null|float|bool|PrimitiveInterface
     */
    private $value;

    /**
     * Value constructor.
     * @param TypeInterface $type
     * @param mixed $value
     */
    public function __construct($value, TypeInterface $type)
    {
        \assert(\is_scalar($value) || $value instanceof PrimitiveInterface);

        $this->type  = $type;
        $this->value = $value;
    }

    /**
     * @return int|string|null|float|bool|PrimitiveInterface
     */
    public function getValue()
    {
        return $this->value;
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
        return \sprintf('%s: %s', $this->type, $this->value);
    }
}
