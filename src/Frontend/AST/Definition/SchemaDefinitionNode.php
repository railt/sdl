<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition;

/**
 * Class SchemaDefinitionNode
 */
class SchemaDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return iterable|SchemaFieldDefinitionNode[]
     */
    public function getSchemaFields(): iterable
    {
        if ($fields = $this->first('SchemaFieldDefinitions', 1)) {
            yield from $fields;
        }
    }
}
