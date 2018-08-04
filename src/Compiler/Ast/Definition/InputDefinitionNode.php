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
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Definition\InputDefinition;

/**
 * Class InputDefinitionNode
 */
class InputDefinitionNode extends TypeDefinitionNode
{
    /**
     * @param Document $document
     * @return TypeDefinition
     */
    public function getTypeDefinition(Document $document): TypeDefinition
    {
        return new InputDefinition($document, $this->getTypeName());
    }
}