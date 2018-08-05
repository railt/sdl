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
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\InterfaceDefinition;
use Railt\SDL\Compiler\Ast\Definition\ObjectDefinitionNode;
use Railt\SDL\Compiler\Processor\DefinitionProcessor;

/**
 * Class InterfaceProcessor
 */
class InterfaceProcessor extends DefinitionProcessor
{
    /**
     * @param RuleInterface|ObjectDefinitionNode $rule
     * @return Definition
     */
    public function resolve(RuleInterface $rule): Definition
    {
        return new InterfaceDefinition($this->document, $rule->getTypeName());
    }
}
