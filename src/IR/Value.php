<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

/**
 * Class Value
 */
class Value extends Definition implements ValueInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * Value constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return array|bool|float|int|mixed|null|string
     */
    public function getValue()
    {
        return $this->value;
    }
}
