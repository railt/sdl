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

/**
 * Class ListValueNode
 */
class ListValueNode extends Rule implements ValueInterface, CompositeValueInterface
{
    /**
     * @return iterable
     */
    public function toPrimitive(): iterable
    {
        foreach ($this->getValues() as $value) {
            yield $value->toPrimitive();
        }
    }

    /**
     * @return iterable|ValueInterface[]
     */
    public function getValues(): iterable
    {
        /** @var ValueInterface $child */
        foreach ($this->getChildren() as $child) {
            yield $child;
        }
    }
}
