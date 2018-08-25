<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Invocation;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\Rule;
use Railt\SDL\Frontend\IR\Value\BooleanValue;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Class BooleanValueNode
 */
class BooleanValueNode extends Rule implements AstValueInterface
{
    /**
     * @return ValueInterface
     */
    public function unpack(): ValueInterface
    {
        /** @var LeafInterface $leaf */
        $leaf = $this->getChild(0);

        return new BooleanValue($leaf->getValue() === 'true', $this->getOffset());
    }
}
