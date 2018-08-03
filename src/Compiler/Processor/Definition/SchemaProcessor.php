<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Processor\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Definition\SchemaDefinition;
use Railt\SDL\Compiler\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Compiler\Processor\BaseProcessor;

/**
 * Class SchemaProcessor
 */
class SchemaProcessor extends BaseProcessor
{
    /**
     * @param RuleInterface|SchemaDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var SchemaDefinition $schema */
        $schema = $ast->getTypeDefinition();

        $this->immediately(function () use ($ast, $schema) {
            $schema->withOffset($ast->getOffset());
            $schema->withDescription($ast->getDescription());
        });

        return $schema;
    }
}
