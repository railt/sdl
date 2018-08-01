<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Definition;

use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Definition\InterfaceDefinition;

/**
 * Class InterfaceDelegate
 */
class InterfaceDelegate extends TypeDefinitionDelegate
{
    /**
     * @param Document $document
     * @return Definition
     */
    protected function create(Document $document): Definition
    {
        return new InterfaceDefinition($document, $this->getTypeName());
    }
}
