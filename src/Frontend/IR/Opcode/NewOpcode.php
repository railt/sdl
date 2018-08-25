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
 * Create an instance of definition $0 type of $1
 */
class NewOpcode extends Opcode
{
    /**
     * DefineOpcode constructor.
     * @param JoinedOpcode|ValueInterface $name
     * @param JoinedOpcode|ValueInterface $context
     */
    public function __construct($name, $context)
    {
        parent::__construct(self::RL_NEW, $name, $context);
    }
}
