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
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\SymbolTable\Value;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type;

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
}
