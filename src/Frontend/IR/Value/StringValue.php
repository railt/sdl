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
 * Class StringValue
 */
class StringValue extends AbstractValue
{
    /**
     * ConstantValue constructor.
     * @param string $value
     * @param int $offset
     */
    public function __construct(string $value, int $offset = 0)
    {
        parent::__construct($value, $offset);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $result = \addcslashes($this->getValue(), '"\\');

        $result = \str_replace(
            ["\b", "\f", "\n", "\r", "\t"],
            ['\u0092', '\u0012', '\u0010', '\u0013', '\u0009'],
            $result
        );

        return \sprintf('"%s"', $result);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return parent::getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '(string)' . parent::__toString();
    }
}
