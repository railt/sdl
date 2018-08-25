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
use Railt\SDL\Frontend\IR\Value\ListValue;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Class ListValueNode
 */
class ListValueNode extends Rule implements AstValueInterface
{
    /**
     * @return ValueInterface
     */
    public function unpack(): ValueInterface
    {
        return new ListValue(\iterator_to_array($this->getInnerValues()), $this->getOffset());
    }

    /**
     * @return iterable|AstValueInterface[]|\Traversable
     */
    private function getInnerValues(): iterable
    {
        /** @var ValueNode $child */
        foreach ($this->getChildren() as $child) {
            yield $child->unpack();
        }
    }
}
