<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Support;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Dependent\ArgumentDefinitionNode;

/**
 * Trait ArgumentsProvider
 */
trait ArgumentsProvider
{
    /**
     * @return iterable|ArgumentDefinitionNode[]
     */
    public function getArgumentNodes(): iterable
    {
        $arguments = $this->first('ArgumentDefinitions', 1);

        if ($arguments instanceof RuleInterface) {
            yield from $arguments;
        }
    }
}
