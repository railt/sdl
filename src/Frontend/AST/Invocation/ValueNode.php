<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Invocation;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\Rule;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Class ValueNode
 */
class ValueNode extends Rule implements AstValueInterface
{
    /**
     * @return ValueInterface
     */
    public function unpack(): ValueInterface
    {
        return $this->getInnerValue()->unpack();
    }

    /**
     * @return AstValueInterface|NodeInterface
     */
    private function getInnerValue(): AstValueInterface
    {
        return $this->getChild(0);
    }
}
