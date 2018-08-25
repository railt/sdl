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
 * Class BooleanValue
 */
class BooleanValue extends AbstractValue
{
    /**
     * ConstantValue constructor.
     * @param bool $value
     * @param int $offset
     */
    public function __construct(bool $value, int $offset = 0)
    {
        parent::__construct($value, $offset);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->getValue() ? 'true' : 'false';
    }

    /**
     * @return bool
     */
    public function getValue(): bool
    {
        return parent::getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '(bool)' . parent::__toString();
    }
}
