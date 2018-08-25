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
 * Class NullValue
 */
class NullValue extends AbstractValue
{
    /**
     * NullValue constructor.
     * @param int $offset
     */
    public function __construct(int $offset = 0)
    {
        parent::__construct(null, $offset);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'null';
    }

    /**
     * @return void
     */
    public function getValue(): void
    {
        return;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '(null)' . parent::__toString();
    }
}
