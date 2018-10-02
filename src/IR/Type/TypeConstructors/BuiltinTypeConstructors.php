<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type\TypeConstructors;

use Railt\SDL\IR\Type\Name;
use Railt\SDL\IR\Type\TypeInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Trait BuiltinTypeConstructors
 */
trait BuiltinTypeConstructors
{
    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return TypeInterface|static
     */
    public static function scalar($name = null): TypeInterface
    {
        \assert(Name::isValid($name), 'Scalar is not generic type');

        $scalar = static::new(static::SCALAR);

        return $name ? static::new($name, $scalar) : $scalar;
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return TypeInterface|static
     */
    public static function enum($name = null): TypeInterface
    {
        \assert(Name::isValid($name), 'Enum is not generic type');

        $enum = static::new(static::ENUM);

        return $name ? static::new($name, $enum) : $enum;
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return TypeInterface|static
     */
    public static function object($name = null): TypeInterface
    {
        \assert(Name::isValid($name), 'Object is not generic type');

        $object = static::new(static::OBJECT);

        return $name ? static::new($name, $object) : $object;
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return TypeInterface|static
     */
    public static function interface($name = null): TypeInterface
    {
        \assert(Name::isValid($name), 'Interface is not generic type');

        $interface = static::new(static::INTERFACE);

        return $name ? static::new($name, $interface) : $interface;
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return TypeInterface|static
     */
    public static function directive($name = null): TypeInterface
    {
        \assert(Name::isValid($name), 'Directive is not generic type');

        $directive = static::new(static::DIRECTIVE);

        return $name ? static::new($name, $directive) : $directive;
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return TypeInterface|static
     */
    public static function schema($name = null): TypeInterface
    {
        \assert(Name::isValid($name), 'Schema is not generic type');

        $schema = static::new(static::SCHEMA);

        return $name ? static::new($name, $schema) : $schema;
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return TypeInterface|static
     */
    public static function union($name = null): TypeInterface
    {
        \assert(Name::isValid($name), 'Union is not generic type');

        $union = static::new(static::UNION);

        return $name ? static::new($name, $union) : $union;
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return TypeInterface|static
     */
    public static function input($name = null): TypeInterface
    {
        \assert(Name::isValid($name), 'Input is not generic type');

        $input = static::new(static::INPUT_OBJECT);

        return $name ? static::new($name, $input) : $input;
    }

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return TypeInterface|static
     */
    public static function any($name = null): TypeInterface
    {
        \assert(Name::isValid($name), 'Any is not generic type');

        $any = static::new(static::ANY);

        return $name ? static::new($name, $any) : $any;
    }
}
