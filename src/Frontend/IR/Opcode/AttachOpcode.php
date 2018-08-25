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
 * Class AttachOpcode
 */
class AttachOpcode extends Opcode
{
    /**
     * AttachOpcode constructor.
     * @param JoinedOpcode|ValueInterface $value
     * @param JoinedOpcode $context
     */
    public function __construct($value, JoinedOpcode $context)
    {
        parent::__construct(self::RL_ATTACH, $value, $context);
    }
}
