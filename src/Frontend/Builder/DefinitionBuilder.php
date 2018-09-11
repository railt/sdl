<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\SDL\Frontend\AST\Definition\TypeDefinitionNode;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class DefinitionBuilder
 */
abstract class DefinitionBuilder implements BuilderInterface
{
    /**
     * @param TypeDefinitionNode $ast
     * @param TypeDefinition $type
     * @return \Generator
     */
    protected function loadDirectives(TypeDefinitionNode $ast, TypeDefinition $type): \Generator
    {
        $type->directives = [];

        foreach ($ast->getDirectiveNodes() as $directive) {
            $type->directives[] = yield $directive;
        }
    }
}
