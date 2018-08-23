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
use Railt\SDL\Frontend\AST\ProvidesName;
use Railt\SDL\Frontend\AST\ProvidesValue;
use Railt\SDL\Frontend\AST\Support\DependentNameProvider;
use Railt\SDL\Frontend\AST\Value\ValueInterface;

/**
 * Class ArgumentInvocationNode
 */
class ArgumentInvocationNode extends Rule implements ProvidesName, ProvidesValue
{
    use DependentNameProvider;

    /**
     * @return ValueInterface
     */
    public function getValue(): ValueInterface
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
