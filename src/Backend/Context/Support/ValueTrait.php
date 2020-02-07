<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context\Support;

use Railt\TypeSystem\Value\ValueInterface;

/**
 * Trait ValueTrait
 */
trait ValueTrait
{
    /**
     * @param ValueInterface $value
     * @return ValueInterface
     */
    protected function value(ValueInterface $value): ValueInterface
    {
        return $value;
    }
}
