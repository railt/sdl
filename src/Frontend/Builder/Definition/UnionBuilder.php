<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Definition;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Ast\Definition\UnionDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class UnionBuilder
 */
class UnionBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|UnionDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $union = new TypeDefinition($ast->getFullName());
        $union->in($file, $ast->getOffset());

        $union->type        = Type::UNION;
        $union->description = $ast->getDescription();

        $this->loadUnionDefinitions($ast, $union);

        return $union;
    }

    /**
     * @param UnionDefinitionNode $ast
     * @param TypeDefinition $union
     */
    protected function loadUnionDefinitions(UnionDefinitionNode $ast, TypeDefinition $union): void
    {
        $union->definitions = [];

        foreach ($ast->getUnionDefinitions() as $definition) {
            $union->definitions[] = $definition;
        }
    }
}
