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
 * Class DescriptionOpcode
 */
class DescriptionOpcode extends Opcode
{
    /**
     * DescriptionOpcode constructor.
     * @param ValueInterface $description
     */
    public function __construct(ValueInterface $description)
    {
        parent::__construct(self::RL_DESCRIPTION, $description);
    }
}
