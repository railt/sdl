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
use Railt\SDL\Frontend\AST\ProvidesOpcode;
use Railt\SDL\Frontend\AST\ProvidesValue;
use Railt\SDL\Frontend\AST\Support\DependentNameProvider;
use Railt\SDL\Frontend\AST\Value\ValueInterface;
use Railt\SDL\Frontend\Context;
use Railt\SDL\Frontend\IR\Opcode\CompareOpcode;
use Railt\SDL\Frontend\IR\Opcode\CallOpcode;
use Railt\SDL\Frontend\IR\Opcode\FetchOpcode;
use Railt\SDL\Frontend\IR\Opcode\DefineOpcode;

/**
 * Class ArgumentInvocationNode
 */
class ArgumentInvocationNode extends Rule implements ProvidesName, ProvidesValue, ProvidesOpcode
{
    use DependentNameProvider;

    /**
     * @param Context $context
     * @return iterable
     */
    public function getOpcodes(Context $context): iterable
    {
        $current = $context->create();

        $definition = yield new FetchOpcode($this->getFullName(), $current, false);
        yield new CompareOpcode($this->getValue(), $definition);
        yield new CallOpcode($definition, $this->getValue());
    }

    /**
     * @return ValueInterface
     */
    public function getValue(): ValueInterface
    {
        return $this->first('Value', 1);
    }
}
