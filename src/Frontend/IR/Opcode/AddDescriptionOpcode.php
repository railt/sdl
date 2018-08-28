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
 * Class AddDescriptionOpcode
 */
class AddDescriptionOpcode extends Opcode
{
    /**
     * AddDescriptionOpcode constructor.
     * @param OpcodeInterface $context
     * @param ValueInterface $description
     */
    public function __construct(OpcodeInterface $context, ValueInterface $description)
    {
        parent::__construct(self::RL_ADD_DESCRIPTION, $context, $description);
    }
}
