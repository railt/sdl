<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Value;

use Railt\Parser\Ast\Rule;

/**
 * Class ValueNode
 */
class ValueNode extends Rule implements ValueInterface
{
    /**
     * @return mixed
     */
    public function toPrimitive()
    {
        return $this->getInnerValue()->toPrimitive();
    }

    /**
     * @return ValueInterface
     */
    public function getInnerValue(): ValueInterface
    {
        return $this->getChild(0);
    }
}
