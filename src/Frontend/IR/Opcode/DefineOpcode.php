<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Opcode;

use Railt\Reflection\Contracts\TypeInterface;
use Railt\SDL\Frontend\IR\JoinableOpcode;
use Railt\SDL\Frontend\IR\Opcode;

/**
 * Create a new definition $0 type of $1 with context $2
 */
class DefineOpcode extends Opcode
{
    /**
     * DefineOpcode constructor.
     * @param string $name
     * @param TypeInterface|JoinableOpcode $type
     * @param null $context
     */
    public function __construct(string $name, $type, $context = null)
    {
        parent::__construct(self::RL_DEFINE, $name, $type, $context);
    }
}
