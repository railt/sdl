<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Type;

/**
 * Class Type
 */
class Type extends BaseType
{
    /**
     * @param string $name
     * @return Type
     */
    public static function new(string $name): Type
    {
        return new static($name, new AnyType());
    }
}
