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
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Class AddFieldOpcode
 */
class AddFieldOpcode extends Opcode
{
    /**
     * AddFieldOpcode constructor.
     * @param Opcode|ValueInterface $value
     * @param Opcode $context
     */
    public function __construct($value, Opcode $context)
    {
        parent::__construct(self::RL_ADD_FIELD, $value, $context);
    }
}
