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
use Railt\Reflection\Definition\ScalarDefinition;
use Railt\SDL\Compiler\Ast\Definition\ScalarDefinitionNode;
use Railt\SDL\Compiler\Processor\BaseProcessor;

/**
 * Class ScalarProcessor
 */
class ScalarProcessor extends BaseProcessor
{
    /**
     * @param RuleInterface|ScalarDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var ScalarDefinition $scalar */
        $scalar = $ast->getTypeDefinition();

        $this->immediately(function () use ($ast, $scalar): void {
            $scalar->withOffset($ast->getOffset());
            $scalar->withDescription($ast->getDescription());
        });

        return $scalar;
    }
}
