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
 * Assertion: Value $0 should be type of $1
 */
class CompareOpcode extends Opcode
{
    /**
     * CompareOpcode constructor.
     * @param mixed $haystack
     * @param mixed $needle
     */
    public function __construct($haystack, $needle)
    {
        parent::__construct(self::RL_ASSERT_COMPARE, $haystack, $needle);
    }
}
