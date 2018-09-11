<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Invocation;

use Railt\Parser\Ast\Rule;

/**
 * Class ConstantValue
 */
class ConstantValueNode extends Rule implements AstValueInterface
{
    /**
     * @return mixed|null
     */
    public function toPrimitive()
    {
        throw new \LogicException('Not implemented yet');
    }
}
