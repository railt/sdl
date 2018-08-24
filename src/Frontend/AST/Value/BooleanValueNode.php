<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Value;

use Railt\Parser\Ast\Rule;

/**
 * Class BooleanValueNode
 */
class BooleanValueNode extends Rule implements ValueInterface
{
    /**
     * @return bool
     */
    public function toPrimitive(): bool
    {
        return $this->toString() === 'true';
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return '(bool)' . $this->getChild(0)->getValue();
    }
}
