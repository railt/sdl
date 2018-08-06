<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Value;

use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler\Builder\Value\ValueInterface;

/**
 * Class ValueNode
 */
class ValueNode extends BaseValueNode
{
    /**
     * @return mixed
     */
    public function toPrimitive()
    {
        return $this->getChild(0)->toPrimitive();
    }
}
