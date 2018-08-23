<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Value;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;

/**
 * Class InputValueNode
 */
class InputValueNode extends Rule implements ValueInterface
{
    /**
     * @return string
     */
    public function toString(): string
    {
        $result = [];

        foreach ($this->getValues() as $key => $value) {
            $result[] = $key->getValue() . ': ' . $value->toString();
        }

        return \sprintf('{%s}', \implode(', ', $result));
    }

    /**
     * @return iterable|ValueInterface[]
     */
    public function toPrimitive(): iterable
    {
        $result = [];

        foreach ($this->getValues() as $key => $value) {
            $result[$key->getValue()] = $value->toPrimitive();
        }

        return $result;
    }

    /**
     * @return iterable|ValueInterface[]
     */
    public function getValues(): iterable
    {
        /** @var RuleInterface $child */
        foreach ($this->getChildren() as $child) {
            yield $this->key($child) => $this->value($child)->getInnerValue();
        }
    }

    /**
     * @param RuleInterface $rule
     * @return LeafInterface
     */
    private function key(RuleInterface $rule): LeafInterface
    {
        /** @var RuleInterface $key */
        $key = $rule->first('Key', 1);

        return $key->getChild(0);
    }

    /**
     * @param RuleInterface $rule
     * @return ValueInterface|NodeInterface
     */
    private function value(RuleInterface $rule): ValueInterface
    {
        /** @var ValueNode $value */
        return $rule->first('Value', 1);
    }
}
