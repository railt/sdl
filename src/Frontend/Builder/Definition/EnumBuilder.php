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
use Railt\SDL\Frontend\Ast\Definition\EnumDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class EnumBuilder
 */
class EnumBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|EnumDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $enum = new TypeDefinition($ast->getFullName());
        $enum->in($file, $ast->getOffset());

        $enum->type = Type::ENUM;
        $enum->description = $ast->getDescription();

        yield from $this->loadEnumValues($ast, $enum);

        return $enum;
    }

    /**
     * @param EnumDefinitionNode $ast
     * @param TypeDefinition $enum
     * @return \Generator
     */
    protected function loadEnumValues(EnumDefinitionNode $ast, TypeDefinition $enum): \Generator
    {
        $enum->values = [];

        foreach ($ast->getEnumValueNodes() as $values) {
            $enum->values[] = yield $values;
        }
    }
}
