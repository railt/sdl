<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type\TypeConstructors;

use Railt\SDL\IR\Type\ListType;
use Railt\SDL\IR\Type\NonNullType;
use Railt\SDL\IR\Type\TypeInterface;

/**
 * Trait GenericTypeConstructors
 */
trait GenericTypeConstructors
{
    /**
     * @param TypeInterface $type
     * @return TypeInterface|static
     */
    public static function listOf(TypeInterface $type): TypeInterface
    {
        return new ListType($type);
    }

    /**
     * @param TypeInterface $type
     * @return TypeInterface|static
     */
    public static function nonNull(TypeInterface $type): TypeInterface
    {
        return new NonNullType($type);
    }
}
