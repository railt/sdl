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
use Railt\Reflection\Contracts\Definition\SchemaDefinition;
use Railt\Reflection\Definition\DirectiveDefinition;
use Railt\Reflection\Contracts\Document;

/**
 * Class SchemaDelegate
 */
class SchemaDelegate extends DefinitionDelegate
{
    /**
     * @param Document $document
     * @return Definition
     */
    protected function bootDefinition(Document $document): Definition
    {
        return new DirectiveDefinition($document, 'Schema');
    }
}
