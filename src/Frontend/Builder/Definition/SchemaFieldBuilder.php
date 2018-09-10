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
use Railt\SDL\Frontend\Ast\Definition\SchemaFieldDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class SchemaFieldBuilder
 */
class SchemaFieldBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|SchemaFieldDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $field = new TypeDefinition($ast->getFullName());
        $field->in($file, $ast->getOffset());

        $field->type        = Type::SCHEMA_FIELD;
        $field->description = $ast->getDescription();

        $field->modifiers = $ast->getHintModifiers();
        $field->hint      = $ast->getHintTypeName();

        return $field;
    }
}
