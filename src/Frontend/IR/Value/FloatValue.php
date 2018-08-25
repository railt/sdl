<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Value;

/**
 * Class FloatValue
 */
class FloatValue extends AbstractValue
{
    /**
     * FloatValue constructor.
     * @param float $value
     * @param int $offset
     */
    public function __construct(float $value, int $offset = 0)
    {
        parent::__construct($value, $offset);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $result = \rtrim(\number_format($this->getValue(), 2), '0.');

        if (\substr_count($result, '.')) {
            return $result;
        }

        return $result . '.0';
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return parent::getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '(float)' . parent::__toString();
    }
}
