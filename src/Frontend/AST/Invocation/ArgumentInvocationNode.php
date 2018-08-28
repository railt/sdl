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
use Railt\SDL\Frontend\AST\Support\DependentNameProvider;
use Railt\SDL\Frontend\Context;
use Railt\SDL\Frontend\IR\Opcode\AddDefinitionOpcode;
use Railt\SDL\Frontend\IR\Opcode\CompareOpcode;
use Railt\SDL\Frontend\IR\Opcode\FetchOpcode;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Class ArgumentInvocationNode
 */
class ArgumentInvocationNode extends Rule implements ProvidesName, ProvidesOpcode
{
    use DependentNameProvider;

    /**
     * @param Context $context
     * @return iterable
     */
    public function getOpcodes(Context $context): iterable
    {
        $current = $context->create();

        $definition = yield new FetchOpcode($this->getNameValue(), $current);

        yield new CompareOpcode($this->getValue(), $definition);
        yield new AddDefinitionOpcode($this->getValue(), $definition);
    }

    /**
     * @return ValueInterface
     */
    public function getKey(): ValueInterface
    {
        return $this->getNameValue();
    }

    /**
     * @return ValueInterface
     */
    public function getValue(): ValueInterface
    {
        /** @var AstValueInterface $child */
        $child = $this->first('Value', 1);

        return $child->unpack();
    }
}
