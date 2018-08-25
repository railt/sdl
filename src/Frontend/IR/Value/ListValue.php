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
 * Class ListValue
 */
class ListValue extends AbstractValue
{
    /**
     * ConstantValue constructor.
     * @param array|ValueInterface[] $values
     * @param int $offset
     */
    public function __construct(array $values, int $offset = 0)
    {
        parent::__construct($values, $offset);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $result = [];

        foreach ($this->getValue() as $value) {
            $result[] = $value->toString();
        }

        return \sprintf('[%s]', \implode(', ', $result));
    }

    /**
     * @return array|ValueInterface[]|AbstractValue[]
     */
    public function getValue(): array
    {
        return parent::getValue();
    }
}
