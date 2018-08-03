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
use Railt\SDL\Compiler\Processor\BaseProcessor;

/**
 * Class EnumProcessor
 */
class EnumProcessor extends BaseProcessor
{
    /**
     * @param RuleInterface|EnumDefinitionNode $ast
     * @return null|TypeDefinition
     */
    public function process(RuleInterface $ast): ?TypeDefinition
    {
        /** @var EnumDefinition $enum */
        $enum = $ast->getTypeDefinition();

        $this->immediately(function () use ($ast, $enum) {
            $enum->withOffset($ast->getOffset());
            $enum->withDescription($ast->getDescription());
        });

        return $enum;
    }
}
