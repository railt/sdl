<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition\Provider;

use Railt\SDL\Frontend\AST\Invocation\DirectiveValueNode;

/**
 * Trait DirectivesProvider
 */
trait DirectivesProvider
{
    /**
     * @return iterable|DirectiveValueNode[]
     */
    public function getDirectiveNodes(): iterable
    {
        if ($directives = $this->first('Directives', 1)) {
            yield from $directives;
        }
    }
}
