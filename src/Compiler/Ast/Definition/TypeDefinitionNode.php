<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\Parser\Ast\Rule;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler\Ast\Common\DescriptionProvider;
use Railt\SDL\Compiler\Ast\Common\DirectivesProvider;
use Railt\SDL\Compiler\Ast\TypeNameNode;

/**
 * Class TypeDefinitionNode
 */
abstract class TypeDefinitionNode extends Rule
{
    use DescriptionProvider;
    use DirectivesProvider;

    /**
     * @return null|string
     */
    public function getTypeName(): ?string
    {
        /** @var TypeNameNode $name */
        $name = $this->first('TypeName', 1);

        return $name ? $name->getTypeName() : null;
    }
}
