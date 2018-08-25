<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Value;

use Railt\Reflection\Contracts\TypeInterface;

/**
 * Class TypeValue
 */
class TypeValue extends AbstractValue
{
    /**
     * TypeValue constructor.
     * @param TypeInterface $value
     * @param int $offset
     */
    public function __construct(TypeInterface $value, int $offset = 0)
    {
        parent::__construct($value, $offset);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->getValue()->getName();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'typeof ' . parent::__toString();
    }

    /**
     * @return TypeInterface
     */
    public function getValue(): TypeInterface
    {
        return parent::getValue();
    }
}
