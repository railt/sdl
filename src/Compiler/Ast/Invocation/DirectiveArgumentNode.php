<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Invocation;

use Railt\Parser\Ast\Rule;
use Railt\Reflection\Contracts\Invocation\TypeInvocation;
use Railt\Reflection\Invocation\Dependent\ArgumentInvocation;
use Railt\Reflection\Invocation\DirectiveInvocation;
use Railt\SDL\Compiler\Ast\Value\ValueInterface;
use Railt\SDL\Compiler\Ast\Value\ValueNode;

/**
 * Class DirectiveArgumentNode
 */
class DirectiveArgumentNode extends Rule
{
    /**
     * @param DirectiveInvocationBuilder $parent
     * @return TypeInvocation|ArgumentInvocation
     */
    public function getTypeInvocation(DirectiveInvocation $parent): TypeInvocation
    {
        $argument = new ArgumentInvocation($parent, $this->getArgumentName(), $this->getArgumentValue()->toPrimitive());
        $argument->withOffset($this->getOffset());

        return $argument;
    }

    /**
     * @return string
     */
    public function getArgumentName(): string
    {
        return $this->first('T_NAME', 1)->getValue();
    }

    /**
     * @return ValueInterface
     */
    public function getArgumentValue(): ValueInterface
    {
        /** @var ValueNode $value */
        $value = $this->first('Value', 1);

        return $value->getInnerValue();
    }
}
