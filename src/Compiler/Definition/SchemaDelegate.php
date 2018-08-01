<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Definition;

use Railt\Parser\Ast\NodeInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Definition\SchemaDefinition;
use Railt\Reflection\Document;

/**
 * Class SchemaDelegate
 */
class SchemaDelegate extends DefinitionDelegate
{
    /**
     * @param DocumentInterface|Document $document
     * @return Definition
     */
    protected function bootDefinition(DocumentInterface $document): Definition
    {
        return new SchemaDefinition($document, $this->getTypeName());
    }
}
