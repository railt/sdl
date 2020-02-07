<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Spec\Constraint;

use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Exception\TypeErrorException;
use Railt\SDL\Frontend\Ast\Value\VariableValueNode;
use Railt\SDL\Spec\SpecificationInterface;

/**
 * Class Variables
 */
class Variables extends Constraint
{
    /**
     * @param NodeInterface $node
     * @param SpecificationInterface $spec
     * @return void
     */
    public static function assert(NodeInterface $node, SpecificationInterface $spec): void
    {
        if (! $node instanceof VariableValueNode) {
            return;
        }

        throw new TypeErrorException(static::notSupported($spec), $node);
    }
}
