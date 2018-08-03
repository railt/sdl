<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Value;

use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;

/**
 * Class InputValueNode
 */
class InputValueNode extends Rule implements ValueInterface, CompositeValueInterface
{
    /**
     * @return iterable
     */
    public function toPrimitive(): iterable
    {
        foreach ($this->getValues() as $key => $value) {
            yield $key => $value->toPrimitive();
        }
    }

    /**
     * @return iterable|ValueInterface[]
     */
    public function getValues(): iterable
    {
        /** @var RuleInterface $child */
        foreach ($this->getChildren() as $child) {
            yield $this->key($child) => $this->value($child);
        }
    }

    /**
     * @param RuleInterface $rule
     * @return ValueInterface|RuleInterface
     */
    private function value(RuleInterface $rule): ValueInterface
    {
        /** @var ValueNode $value */
        $value = $rule->first('Value', 1);

        return $value->getInnerValue();
    }

    /**
     * @param RuleInterface $rule
     * @return string
     */
    private function key(RuleInterface $rule): string
    {
        /** @var RuleInterface $key */
        $key = $rule->first('Key', 1);

        return $key->getChild(0)->getValue();
    }
}
