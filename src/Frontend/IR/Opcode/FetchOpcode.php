<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Opcode;

use Railt\SDL\Frontend\IR\Opcode;

/**
 * Tries to get a reference to the definition ($0) from the selected context ($1) using recursion ($2).
 */
class FetchOpcode extends Opcode
{
    /**
     * FetchOpcode constructor.
     * @param mixed $needle
     * @param null $haystack
     * @param bool $strict
     */
    public function __construct($needle, $haystack = null, bool $strict = true)
    {
        parent::__construct(self::RL_FETCH, $needle, $haystack, $strict);
    }
}
