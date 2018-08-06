<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Value;

/**
 * Class ListValueNode
 */
class ListValueNode extends BaseValueNode
{
    /**
     * @return iterable|BaseValueNode[]
     */
    public function toPrimitive(): iterable
    {
        return $this->getChildren();
    }
}
