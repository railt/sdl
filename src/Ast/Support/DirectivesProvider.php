<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Support;

use Railt\SDL\Ast\Invocation\DirectiveInvocationNode;

/**
 * Trait DirectivesProvider
 */
trait DirectivesProvider
{
    /**
     * @return iterable|DirectiveInvocationNode[]
     */
    public function getDirectiveNodes(): iterable
    {
        $directives = $this->first('Directives', 1);

        if ($directives) {
            yield from $directives;
        }
    }
}
