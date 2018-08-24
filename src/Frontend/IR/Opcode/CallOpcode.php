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
 * Create a new definition $0 type of $1 with context $2
 */
class CallOpcode extends Opcode
{
    /**
     * CallOpcode constructor.
     * @param $type
     * @param mixed ...$values
     */
    public function __construct($type, ...$values)
    {
        parent::__construct(self::RL_CALL, $type, ...$values);
    }
}
