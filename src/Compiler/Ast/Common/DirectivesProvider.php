<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Common;

use Railt\SDL\Compiler\Ast\Dependent\DirectiveNode;

/**
 * Trait DirectivesProvider
 */
trait DirectivesProvider
{
    /**
     * @return iterable|DirectiveNode[]
     */
    public function getDirectives(): iterable
    {
        $directives = $this->first('Directives', 1);

        if ($directives) {
            foreach ($directives as $directive) {
                yield $directive;
            }
        }
    }
}
