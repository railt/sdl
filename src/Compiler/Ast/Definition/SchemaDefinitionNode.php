<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\SDL\Compiler\Ast\Dependent\SchemaFieldDefinitionNode;

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
        $fields = $this->first('SchemaFields', 1);

        foreach ($fields as $field) {
            yield $field;
        }
    }
}
