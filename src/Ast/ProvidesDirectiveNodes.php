<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast;

use Railt\SDL\Ast\Invocation\DirectiveInvocationNode;

/**
 * Interface ProvidesDirectiveNodes
 */
interface ProvidesDirectiveNodes
{
    /**
     * @return iterable|DirectiveInvocationNode[]
     */
    public function getDirectiveNodes(): iterable;
}
