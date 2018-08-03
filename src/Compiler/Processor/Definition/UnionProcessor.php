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
use Railt\Reflection\Definition\UnionDefinition;
use Railt\SDL\Compiler\Ast\Definition\UnionDefinitionNode;

/**
 * Class UnionProcessor
 */
class UnionProcessor extends TypeDefinitionProcessor
{
    /**
     * @param RuleInterface|UnionDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var UnionDefinition $union */
        $union = $ast->getTypeDefinition($this->document);

        $this->processDefinition($ast, $union);
        $this->processDirectives($ast, $union);

        return $union;
    }
}
