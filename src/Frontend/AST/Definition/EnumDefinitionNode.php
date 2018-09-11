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
 * Class EnumDefinitionNode
 */
class EnumDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return iterable|EnumValueDefinitionNode[]
     */
    public function getEnumValueNodes(): iterable
    {
        if ($values = $this->first('EnumValueDefinitions', 1)) {
            yield from $values;
        }
    }
}
