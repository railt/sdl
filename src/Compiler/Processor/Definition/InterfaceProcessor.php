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
use Railt\Reflection\Definition\InterfaceDefinition;
use Railt\SDL\Compiler\Ast\Definition\InterfaceDefinitionNode;
use Railt\SDL\Compiler\Processor\BaseProcessor;

/**
 * Class InterfaceProcessor
 */
class InterfaceProcessor extends BaseProcessor
{
    /**
     * @param RuleInterface|InterfaceDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var InterfaceDefinition $interface */
        $interface = $ast->getTypeDefinition();

        $this->immediately(function () use ($ast, $interface): void {
            $interface->withOffset($ast->getOffset());
            $interface->withDescription($ast->getDescription());
        });

        return $interface;
    }
}
