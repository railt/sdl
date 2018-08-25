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
use Railt\SDL\Frontend\IR\OpcodeInterface;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Tries to get a reference to the definition $0 from the $1 or the selected context parents (recursive).
 */
class FetchDeepOpcode extends Opcode
{
    /**
     * FetchDeepOpcode constructor.
     * @param ValueInterface $needle
     * @param OpcodeInterface $haystack
     */
    public function __construct(ValueInterface $needle, OpcodeInterface $haystack)
    {
        parent::__construct(self::RL_FETCH_DEEP, $needle, $haystack);
    }
}
