<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\SDL\Compiler\Ast\Dependent\EnumValueDefinitionNode;

/**
 * Class EnumDefinitionNode
 */
class EnumDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return iterable|EnumValueDefinitionNode[]
     */
    public function getEnumValues(): iterable
    {
        $values = $this->first('EnumValues', 1);

        if ($values) {
            foreach ($values as $value) {
                yield $value;
            }
        }
    }
}
