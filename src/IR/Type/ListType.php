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
 * Class ListType
 */
class ListType extends WrappingType
{
    /**
     * ListType constructor.
     * @param TypeInterface $of
     */
    public function __construct(TypeInterface $of)
    {
        parent::__construct(static::LIST, $of);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '[' . $this->of . ']';
    }
}
