<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context\Support;

use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\StringValue;

/**
 * Trait DescriptionReaderTrait
 */
trait DescriptionReaderTrait
{
    /**
     * @param Node $node
     * @return string|null
     */
    protected function descriptionOf(Node $node): ?string
    {
        if (! \property_exists($node, 'description')) {
            return null;
        }

        if ($node->description instanceof StringValue) {
            return $node->description->toPHPValue();
        }

        return null;
    }
}
