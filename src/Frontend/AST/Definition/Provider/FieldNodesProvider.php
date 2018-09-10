<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition\Provider;

use Railt\SDL\Frontend\Ast\Definition\FieldDefinitionNode;

/**
 * Trait FieldNodesProvider
 */
trait FieldNodesProvider
{
    /**
     * @return iterable|FieldDefinitionNode[]
     */
    public function getFieldNodes(): iterable
    {
        if ($fields = $this->first('FieldDefinitions', 1)) {
            yield from $fields;
        }
    }
}
