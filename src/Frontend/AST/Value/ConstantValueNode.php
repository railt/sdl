<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Value;

use Railt\Parser\Ast\Rule;

/**
 * Class ConstantValueNode
 */
class ConstantValueNode extends Rule implements ValueInterface
{
    /**
     * @return string
     */
    public function toPrimitive(): string
    {
        return (string)$this->getChild(0)->getValue();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return '(const)' . $this->toPrimitive();
    }
}
