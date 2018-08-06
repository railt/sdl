<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Value;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;

/**
 * Class InputValueNode
 */
class InputValueNode extends BaseValueNode
{
    /**
     * @return iterable|BaseValueNode[]
     */
    public function toPrimitive(): iterable
    {
        return $this->getValues();
    }

    /**
     * @return iterable|BaseValueNode[]
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
     * @return string
     */
    private function key(RuleInterface $rule): string
    {
        /** @var RuleInterface $key */
        $key = $rule->first('Key', 1);

        return $key->getChild(0)->getValue();
    }

    /**
     * @param RuleInterface $rule
     * @return BaseValueNode|NodeInterface
     */
    private function value(RuleInterface $rule): BaseValueNode
    {
        /** @var ValueNode $value */
        return $rule->first('Value', 1);
    }
}
