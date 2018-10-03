<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type\TypeConstructors;

use Railt\SDL\IR\Type\TypeInterface;

/**
 * Class RuntimeTypeConstructors
 */
trait RuntimeTypeConstructors
{
    /**
     * @return TypeInterface|static
     */
    public static function string(): TypeInterface
    {
        return static::new(static::STRING);
    }

    /**
     * @return TypeInterface|static
     */
    public static function int(): TypeInterface
    {
        return static::new(static::INT);
    }

    /**
     * @return TypeInterface|static
     */
    public static function bool(): TypeInterface
    {
        return static::new(static::BOOLEAN);
    }

    /**
     * @return TypeInterface|static
     */
    public static function float(): TypeInterface
    {
        return static::new(static::FLOAT);
    }

    /**
     * @return TypeInterface|static
     */
    public static function id(): TypeInterface
    {
        return static::new(static::ID);
    }

    /**
     * @return TypeInterface|static
     */
    public static function date(): TypeInterface
    {
        return static::new(static::DATE_TIME);
    }

    /**
     * @return TypeInterface|static
     */
    public static function null(): TypeInterface
    {
        return static::new(static::NULL);
    }

    /**
     * @return TypeInterface
     */
    public static function const(): TypeInterface
    {
        return static::new(static::CONST);
    }

    /**
     * @return TypeInterface
     */
    public static function type(): TypeInterface
    {
        return static::new(static::TYPE);
    }
}
