<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type\InternalTypes;

/**
 * Trait BuiltinTypeInheritance
 */
trait BuiltinTypes
{
    /**
     * @return array
     */
    protected static function getBuiltinTypes(): array
    {
        return [
            static::ROOT_TYPE    => $any = self::createInternalType(static::ROOT_TYPE),
            static::INTERFACE    => $interface = self::createInternalType(static::INTERFACE),
            static::OBJECT       => $object = self::createInternalType(static::OBJECT, $interface),
            static::INPUT_OBJECT => $input = self::createInternalType(static::INPUT_OBJECT, $object),
            static::UNION        => $union = self::createInternalType(static::UNION, $object),
            static::SCALAR       => $scalar = self::createInternalType(static::SCALAR),
            static::ENUM         => $enum = self::createInternalType(static::ENUM, $scalar),
            static::DIRECTIVE    => $directive = self::createInternalType(static::DIRECTIVE),
            static::SCHEMA       => $schema = self::createInternalType(static::SCHEMA),
            static::DOCUMENT     => $document = self::createInternalType(static::DOCUMENT),
        ];
    }
}
