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
use Railt\Reflection\Definition\InputUnionDefinition;
use Railt\SDL\Compiler\Ast\Definition\InputUnionDefinitionNode;

/**
 * Class InputUnionProcessor
 */
class InputUnionProcessor extends TypeDefinitionProcessor
{
    /**
     * @param RuleInterface|InputUnionDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var InputUnionDefinition $inputUnion */
        $inputUnion = $ast->getTypeDefinition($this->document);

        $this->processDefinition($ast, $inputUnion);
        $this->processDirectives($ast, $inputUnion);

        return $inputUnion;
    }
}
