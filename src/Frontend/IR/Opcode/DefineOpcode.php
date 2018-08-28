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
use Railt\SDL\Frontend\IR\Value\ConstantValue;
use Railt\SDL\Frontend\IR\Value\NullValue;
use Railt\SDL\Frontend\IR\Value\TypeValue;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Create a new definition $0 type of $1
 */
class DefineOpcode extends Opcode
{
    /**
     * DefineOpcode constructor.
     * @param ValueInterface $name
     * @param TypeValue $type
     */
    public function __construct(ValueInterface $name, TypeValue $type)
    {
        \assert($name instanceof ConstantValue || $name instanceof NullValue);

        parent::__construct(self::RL_DEFINE, $name, $type);
    }
}
