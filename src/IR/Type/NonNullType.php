<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type;

/**
 * Class NonNullType
 */
class NonNullType extends WrappingType
{
    /**
     * NonNullType constructor.
     * @param TypeInterface $parent
     */
    public function __construct(TypeInterface $parent)
    {
        parent::__construct(static::NON_NULL, $parent);

        \assert(! $this->is($parent), __CLASS__ . ' can not be wrapped by itself');
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->of . '!';
    }
}
