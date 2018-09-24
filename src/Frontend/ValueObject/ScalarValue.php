<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\ValueObject;

/**
 * Class ScalarValue
 */
class ScalarValue extends BaseStruct
{
    /**
     * ScalarValue constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}
