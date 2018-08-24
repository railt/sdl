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
use Railt\SDL\Frontend\AST\Value\ValueInterface;

/**
 * Class InputInvocationNode
 */
class InputInvocationNode extends Rule implements ProvidesType, ValueInterface
{
    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return Type::of(Type::INPUT_OBJECT);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $result = [];

        /**
         * @var string $name
         * @var ValueInterface $value
         */
        foreach ($this->getValues() as [$name, $value]) {
            $result[] = $name . ': ' . $value->toString();
        }

        return \sprintf('{%s}', \implode(', ', $result));
    }

    /**
     * @return iterable|array[]
     */
    public function getValues(): iterable
    {
        /** @var RuleInterface $arguments */
        $arguments = $this->first('InputInvocationArguments', 1);

        if ($arguments) {
            /** @var ArgumentInvocationNode $child */
            foreach ($arguments as $child) {
                yield $child => [$child->getFullName(), $child->getValue()];
            }
        }
    }

    /**
     * @return iterable|ValueInterface[]
     */
    public function toPrimitive(): iterable
    {
        $result = [];

        /**
         * @var RuleInterface $ast
         * @var string $name
         * @var ValueInterface $value
         */
        foreach ($this->getValues() as $ast => [$name, $value]) {
            $result[$name] = $value->toPrimitive();
        }

        return $result;
    }
}
