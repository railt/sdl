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

/**
 * Class InterfaceProcessor
 */
class InterfaceProcessor extends TypeDefinitionProcessor
{
    /**
     * @param RuleInterface|InterfaceDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var InterfaceDefinition $interface */
        $interface = $ast->getTypeDefinition($this->document);

        $this->processDefinition($ast, $interface);
        $this->processDirectives($ast, $interface);

        return $interface;
    }
}
