<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition\Provider;

use Railt\SDL\Frontend\Ast\Definition\ArgumentDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\FieldDefinitionNode;

/**
 * Trait ArgumentNodesProvider
 */
trait ArgumentNodesProvider
{
    /**
     * @return iterable|ArgumentDefinitionNode[]
     */
    public function getArgumentNodes(): iterable
    {
        if ($arguments = $this->first('ArgumentDefinitions', 1)) {
            yield from $arguments;
        }
    }
}
