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
use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\TypeInterface;
use Railt\Reflection\Type;
use Railt\SDL\Frontend\AST\ProvidesType;
use Railt\SDL\Frontend\IR\Value\InputValue;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Class InputInvocationNode
 */
class InputInvocationNode extends Rule implements ProvidesType, AstValueInterface
{
    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::INPUT_OBJECT);
    }

    /**
     * @return ValueInterface
     */
    public function unpack(): ValueInterface
    {
        return new InputValue($this->getInnerValues(), $this->getOffset());
    }

    /**
     * @return iterable|ValueInterface[]
     */
    private function getInnerValues(): iterable
    {
        /** @var RuleInterface $arguments */
        $arguments = $this->first('InputInvocationArguments', 1);

        if ($arguments) {
            /** @var ArgumentInvocationNode $child */
            foreach ($arguments as $child) {
                yield $child->getKey() => $child->getValue();
            }
        }
    }
}
