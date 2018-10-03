<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type\InternalTypes;

use Railt\SDL\IR\Type;
use Railt\SDL\IR\Type\TypeInterface;

/**
 * Trait RuntimeTypes
 */
trait RuntimeTypes
{
    /**
     * @return array|TypeInterface[]
     */
    protected static function getRuntimeTypes(): array
    {
        return [
            //
            // NOTE: It is worth noting that the ID, DateTime and others
            // are not independent types that can be deduced during the
            // early static binding.
            //
            // In particular, the type of the ID can be derived from a
            // String or any Int or Float. The hierarchy of scalars
            // determines only the situation when a type can be converted
            // to a parent without losing a piece of data.
            //
            self::STRING    => $string = self::createInternalType(self::STRING, Type::scalar()),
            self::FLOAT     => $float = self::createInternalType(self::FLOAT, $string),
            self::INT       => $int = self::createInternalType(self::INT, $float),
            self::BOOLEAN   => $bool = self::createInternalType(self::BOOLEAN, $string),
            self::NULL      => $null = self::createInternalType(self::NULL, Type::scalar()),
            self::ID        => $id = self::createInternalType(self::ID, $string),
            self::DATE_TIME => $date = self::createInternalType(self::DATE_TIME, $string),
            self::CONST     => $const = self::createInternalType(self::CONST, $string),
            self::TYPE      => $type = self::createInternalType(self::TYPE, Type::any()),
        ];
    }
}
