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
use Railt\SDL\IR\ValueInterface;

/**
 * Class DescriptionValue
 */
class DescriptionValueNode extends Rule implements AstValueInterface
{
    /**
     * @return string
     */
    public function toPrimitive(): string
    {
        return $this->toString()->toPrimitive();
    }

    /**
     * @param Readable $file
     * @return ValueInterface
     */
    public function toValue(Readable $file): ValueInterface
    {
        return $this->toString()->toValue($file);
    }

    /**
     * @return StringValueNode
     */
    private function toString(): StringValueNode
    {
        return $this->getChild(0);
    }
}
