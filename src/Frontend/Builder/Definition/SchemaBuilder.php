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
use Railt\Reflection\Contracts\Definition\SchemaDefinition;
use Railt\SDL\Frontend\AST\Definition\SchemaDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|SchemaDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        // TODO detach from reflection component
        $schema = new TypeDefinition($ast->getFullName() ?: SchemaDefinition::DEFAULT_SCHEMA_NAME);
        $schema->in($file, $ast->getOffset());

        $schema->type = Type::of(Type::SCHEMA);
        $schema->description = $ast->getDescription();

        yield from $this->loadDirectives($ast, $schema);
        yield from $this->loadSchemaFields($ast, $schema);

        return $schema;
    }

    /**
     * @param SchemaDefinitionNode $ast
     * @param TypeDefinition $schema
     * @return \Generator
     */
    protected function loadSchemaFields(SchemaDefinitionNode $ast, TypeDefinition $schema): \Generator
    {
        $schema->fields = [];

        foreach ($ast->getSchemaFields() as $field) {
            $schema->fields[] = yield $field;
        }
    }
}
