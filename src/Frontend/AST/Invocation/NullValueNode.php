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
use Railt\SDL\Frontend\IR\Value\NullValue;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Class NullValueNode
 */
class NullValueNode extends Rule implements AstValueInterface
{
    /**
     * @return ValueInterface
     */
    public function unpack(): ValueInterface
    {
        return new NullValue($this->getOffset());
    }
}
