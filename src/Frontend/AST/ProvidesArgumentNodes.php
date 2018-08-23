<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST;

use Railt\SDL\Frontend\AST\Dependent\ArgumentDefinitionNode;

/**
 * Interface ProvidesArguments
 */
interface ProvidesArgumentNodes
{
    /**
     * @return iterable|ArgumentDefinitionNode[]
     */
    public function getArgumentNodes(): iterable;
}
