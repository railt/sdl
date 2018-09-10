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
 * Interface TypeHintInterface
 */
interface TypeHintInterface
{
    /**
     * @var int
     */
    public const IS_LIST = 0b0001;

    /**
     * @var int
     */
    public const IS_NOT_NULL = 0b0010;

    /**
     * @var int
     */
    public const IS_LIST_OF_NOT_NULL = 0b0100;

    /**
     * Returns a Boolean value that indicates that the type
     * reference is a child of the List type.
     *
     * @return bool
     */
    public function isList(): bool;

    /**
     * Returns a Boolean value that indicates that
     * the type reference is a NonNull type.
     *
     * @return bool
     */
    public function isNonNull(): bool;

    /**
     * Returns a Boolean value that indicates that
     * the type reference is a NonNull + List type.
     *
     * @return bool
     */
    public function isListOfNonNulls(): bool;
}
