<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\SDL\Compiler\Ast\Dependent\InputFieldDefinitionNode;

/**
 * Class InputDefinitionNode
 */
class InputDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return iterable|InputFieldDefinitionNode[]
     */
    public function getInputFields(): iterable
    {
        $fields = $this->first('InputFieldDefinitions', 1);

        if ($fields) {
            foreach ($fields as $field) {
                yield $field;
            }
        }
    }
}
