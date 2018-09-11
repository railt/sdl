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
 * Class InputDefinitionNode
 */
class InputDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return iterable|InputFieldDefinitionNode[]
     */
    public function getInputFieldNodes(): iterable
    {
        if ($fields = $this->first('InputFieldDefinitions', 1)) {
            yield from $fields;
        }
    }
}
