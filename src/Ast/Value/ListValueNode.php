<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Value;

use Railt\Parser\Ast\Rule;

/**
 * Class ListValueNode
 */
class ListValueNode extends Rule implements ValueInterface
{
    /**
     * @return string
     */
    public function toString(): string
    {
        $values = \array_map(function (ValueInterface $value) {
            return $value->toString();
        }, \iterator_to_array($this->getValues()));

        return \sprintf('[%s]', \implode(', ', $values));
    }

    /**
     * @return iterable|ValueInterface[]
     */
    public function toPrimitive(): iterable
    {
        return \array_map(function (ValueInterface $value) {
            return $value->toPrimitive();
        }, \iterator_to_array($this->getValues()));
    }

    /**
     * @return iterable|ValueInterface[]
     */
    public function getValues(): iterable
    {
        foreach ($this->getChildren() as $children) {
            yield $children->getInnerValue();
        }
    }
}
