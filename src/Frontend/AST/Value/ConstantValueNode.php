<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Value;

/**
 * Class ConstantValue
 */
class ConstantValueNode extends AbstractAstValueNode
{
    /**
     * @return string
     */
    protected function parse(): string
    {
        return $this->getChild(0)->getValue();
    }
}
