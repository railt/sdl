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

/**
 * Class SchemaProcessor
 */
class SchemaProcessor extends TypeDefinitionProcessor
{
    /**
     * @param RuleInterface|SchemaDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var SchemaDefinition $schema */
        $schema = $ast->getTypeDefinition($this->document);

        $this->processDefinition($ast, $schema);
        $this->processDirectives($ast, $schema);

        return $schema;
    }
}
