<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Scalar;

use Railt\Parser\Ast\Rule;

/**
 * Class NullValue
 */
class NullValueNode extends Rule implements ScalarInterface
{
    /**
     * @return mixed|null
     */
    public function toPrimitive()
    {
        return null;
    }
}
