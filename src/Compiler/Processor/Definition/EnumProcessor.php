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
use Railt\Reflection\Definition\EnumDefinition;
use Railt\SDL\Compiler\Ast\Definition\EnumDefinitionNode;

/**
 * Class EnumProcessor
 */
class EnumProcessor extends TypeDefinitionProcessor
{
    /**
     * @param RuleInterface|EnumDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var EnumDefinition $enum */
        $enum = $ast->getTypeDefinition($this->document);

        $this->processDefinition($ast, $enum);
        $this->processDirectives($ast, $enum);

        return $enum;
    }
}
