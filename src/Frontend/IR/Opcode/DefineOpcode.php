<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Opcode;

use Railt\SDL\Frontend\IR\JoinedOpcode;
use Railt\SDL\Frontend\IR\Opcode;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Create a new definition $0 type of $1
 */
class DefineOpcode extends Opcode
{
    /**
     * DefineOpcode constructor.
     * @param ValueInterface $name
     * @param JoinedOpcode|ValueInterface $type
     */
    public function __construct(ValueInterface $name, $type)
    {
        parent::__construct(self::RL_DEFINE, $name, $type);
    }
}
