<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Invocation;

use Railt\Io\Readable;
use Railt\Parser\Ast\Rule;
use Railt\SDL\IR\Value;
use Railt\SDL\IR\ValueInterface;

/**
 * Class NullValue
 */
class NullValueNode extends Rule implements AstValueInterface
{
    /**
     * @return mixed|null
     */
    public function toPrimitive()
    {
        return null;
    }

    /**
     * @param Readable $file
     * @return ValueInterface
     */
    public function toValue(Readable $file): ValueInterface
    {
        return (new Value(null))->in($file, $this->getOffset());
    }
}
