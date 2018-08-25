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
 * Class IntValue
 */
class IntValue extends AbstractValue
{
    /**
     * FloatValue constructor.
     * @param int $value
     * @param int $offset
     */
    public function __construct(int $value, int $offset = 0)
    {
        parent::__construct($value, $offset);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return (string)$this->getValue();
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return parent::getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '(int)' . parent::__toString();
    }
}
