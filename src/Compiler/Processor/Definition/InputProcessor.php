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
use Railt\Reflection\Definition\InputDefinition;
use Railt\SDL\Compiler\Ast\Definition\InputDefinitionNode;

/**
 * Class InputProcessor
 */
class InputProcessor extends TypeDefinitionProcessor
{
    /**
     * @param RuleInterface|InputDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var InputDefinition $input */
        $input = $ast->getTypeDefinition($this->document);

        $this->processDefinition($ast, $input);
        $this->processDirectives($ast, $input);

        return $input;
    }
}
