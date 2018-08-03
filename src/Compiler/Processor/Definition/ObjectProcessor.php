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
use Railt\SDL\Compiler\Processor\BaseProcessor;

/**
 * Class DirectiveDefinition
 */
class ObjectProcessor extends BaseProcessor
{
    /**
     * @param RuleInterface|ObjectDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var ObjectDefinition $object */
        $object = $ast->getTypeDefinition();

        $this->immediately(function () use ($ast, $object): void {
            $object->withOffset($ast->getOffset());
            $object->withDescription($ast->getDescription());
        });

        return $object;
    }
}
