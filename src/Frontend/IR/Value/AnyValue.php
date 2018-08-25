<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Value;

/**
 * Class AnyValue
 */
class AnyValue extends AbstractValue
{
    /**
     * @return string
     */
    public function toString(): string
    {
        return '?';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '(any)' . parent::__toString();
    }
}
