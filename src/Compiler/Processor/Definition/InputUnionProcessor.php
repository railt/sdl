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
use Railt\SDL\Compiler\Processor\BaseProcessor;

/**
 * Class InputUnionProcessor
 */
class InputUnionProcessor extends BaseProcessor
{
    /**
     * @param RuleInterface|InputUnionDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var InputUnionDefinition $inputUnion */
        $inputUnion = $ast->getTypeDefinition();

        $this->immediately(function () use ($ast, $inputUnion): void {
            $inputUnion->withOffset($ast->getOffset());
            $inputUnion->withDescription($ast->getDescription());
        });

        return $inputUnion;
    }
}
