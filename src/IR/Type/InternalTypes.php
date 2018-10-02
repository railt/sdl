<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type;

use Railt\SDL\IR\Type\InternalTypes\BuiltinTypes;
use Railt\SDL\IR\Type\InternalTypes\RuntimeTypes;

/**
 * Trait InternalTypes
 */
trait InternalTypes
{
    use BuiltinTypes;
    use RuntimeTypes;

    /**
     * @var array|TypeInterface[]
     */
    protected static $internal = [];

    /**
     * @return void
     */
    protected static function bootInternalTypes(): void
    {
        static::$internal = self::getInternalTypes();
    }

    /**
     * @return array|TypeInterface[]
     */
    protected static function getInternalTypes(): array
    {
        return \array_merge(static::getBuiltinTypes(), static::getRuntimeTypes());
    }

    /**
     * @param string $name
     * @param null|TypeInterface $of
     * @param \Closure $otherwise
     * @return TypeInterface
     */
    protected static function getInternalType(string $name, ?TypeInterface $of, \Closure $otherwise): TypeInterface
    {
        if (isset(static::$internal[$name])) {
            $subtypeOfInternal = ! $of || $of->typeOf(static::$internal[$name]);

            return $subtypeOfInternal ? static::$internal[$name] : $otherwise();
        }

        return $otherwise();
    }

    /**
     * @param string|TypeNameInterface $name
     * @param null $parent
     * @return TypeInterface
     */
    protected static function createInternalType(string $name, $parent = null): TypeInterface
    {
        return self::new(Name::fromString($name)->lock(), $parent);
    }
}
