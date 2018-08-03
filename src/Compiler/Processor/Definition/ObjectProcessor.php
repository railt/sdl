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
use Railt\Reflection\Definition\ObjectDefinition;
use Railt\SDL\Compiler\Ast\Definition\ObjectDefinitionNode;

/**
 * Class DirectiveDefinition
 */
class ObjectProcessor extends TypeDefinitionProcessor
{
    /**
     * @param RuleInterface|ObjectDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var ObjectDefinition $object */
        $object = $ast->getTypeDefinition($this->document);

        $this->processDefinition($ast, $object);
        $this->processDirectives($ast, $object);

        return $object;
    }
}
