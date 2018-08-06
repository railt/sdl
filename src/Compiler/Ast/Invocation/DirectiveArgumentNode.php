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
use Railt\SDL\Compiler\Ast\Value\BaseValueNode;
use Railt\SDL\Compiler\Ast\Value\ValueNode;
use Railt\SDL\Compiler\Builder\Value\BaseValue;

/**
 * Class DirectiveArgumentNode
 */
class DirectiveArgumentNode extends Rule
{
    /**
     * @return string
     */
    public function getArgumentName(): string
    {
        return $this->first('T_NAME', 1)->getValue();
    }

    /**
     * @return BaseValueNode
     */
    public function getArgumentValue(): BaseValueNode
    {
        return $this->first('Value', 1);
    }
}
