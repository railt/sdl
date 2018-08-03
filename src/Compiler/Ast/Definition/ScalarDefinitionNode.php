<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Definition\ScalarDefinition;

/**
 * Class ScalarDefinitionNode
 */
class ScalarDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition
    {
        return new ScalarDefinition($this->getDocument(), $this->getTypeName());
    }
}
