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

/**
 * Class NullValueNode
 */
class NullValueNode extends BaseValueNode
{
    /**
     * @return mixed|null
     */
    public function toPrimitive()
    {
        return null;
    }
}
