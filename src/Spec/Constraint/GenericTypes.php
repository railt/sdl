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
use Railt\SDL\Frontend\Ast\TypeName;
use Railt\SDL\Spec\SpecificationInterface;

/**
 * Class GenericTypes
 */
class GenericTypes extends Constraint
{
    /**
     * @param NodeInterface $node
     * @param SpecificationInterface $spec
     * @return void
     */
    public static function assert(NodeInterface $node, SpecificationInterface $spec): void
    {
        if (! $node instanceof TypeName) {
            return;
        }

        if ($node->arguments !== []) {
            throw new TypeErrorException(static::notSupported($spec), $node);
        }
    }
}
