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
use Railt\Reflection\Definition\DirectiveDefinition;
use Railt\SDL\Compiler\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Compiler\Processor\BaseProcessor;

/**
 * Class DirectiveDefinition
 */
class DirectiveProcessor extends BaseProcessor
{
    /**
     * @param RuleInterface|DirectiveDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var DirectiveDefinition $directive */
        $directive = $ast->getTypeDefinition();

        $this->immediately(function () use ($ast, $directive) {
            $directive->withOffset($ast->getOffset());
            $directive->withDescription($ast->getDescription());
        });

        return $directive;
    }
}
