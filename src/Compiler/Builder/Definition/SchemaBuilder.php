<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\SchemaDefinition;
use Railt\SDL\Compiler\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Compiler\Ast\Dependent\SchemaFieldDefinitionNode;
use Railt\SDL\Compiler\Ast\TypeHintNode;
use Railt\SDL\Compiler\Builder\Builder;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder extends Builder
{
    /**
     * @var string
     */
    private const FIELD_QUERY = 'query';

    /**
     * @var string
     */
    private const FIELD_MUTATION = 'mutation';

    /**
     * @var string
     */
    private const FIELD_SUBSCRIPTION = 'subscription';

    /**
     * @param RuleInterface|SchemaDefinitionNode $rule
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, Definition $parent): Definition
    {
        $schema = new SchemaDefinition($parent->getDocument(), $rule->getTypeName());
        $schema->withOffset($rule->getOffset());
        $schema->withDescription($rule->getDescription());

        $this->when->runtime(function () use ($rule, $schema) {
            foreach ($rule->getDirectives() as $ast) {
                $schema->withDirective($this->dependent($ast, $schema));
            }
        });

        $this->buildSchemaFields($rule, $schema);

        return $schema;
    }

    /**
     * @param SchemaDefinitionNode $rule
     * @param SchemaDefinition $schema
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function buildSchemaFields(SchemaDefinitionNode $rule, SchemaDefinition $schema): void
    {
        foreach ($rule->getSchemaFields() as $ast) {
            $name = $ast->getFieldName();
            $hint = $ast->getTypeHint();

            $this->validateModifiers($name, $hint, $schema);
            $this->validateFieldName($name, $ast, $schema);

            $this->when->resolving(function () use ($name, $schema, $hint) {
                $type = $this->load($hint->getTypeName(), $schema);

                if (! ($type instanceof Definition\ObjectDefinition)) {
                    $error = 'Schema field %s<SchemaField> should return Object type, but %s given';
                    throw (new TypeConflictException(\sprintf($error, $name, $type)))
                        ->throwsIn($schema->getFile(), $hint->getOffset());
                }

                switch ($name) {
                    case self::FIELD_QUERY:
                        $schema->withQuery($type);
                        break;

                    case self::FIELD_MUTATION:
                        $schema->withMutation($type);
                        break;

                    case self::FIELD_SUBSCRIPTION:
                        $schema->withSubscription($type);
                        break;
                }
            });
        }
    }

    /**
     * @param string $field
     * @param TypeHintNode $hint
     * @param SchemaDefinition $schema
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function validateModifiers(string $field, TypeHintNode $hint, SchemaDefinition $schema): void
    {
        if ($hint->getModifiers() !== 0) {
            $error = 'Schema field %s<SchemaField> should be a Nullable and Non-List';
            throw (new TypeConflictException(\sprintf($error, $field)))
                ->throwsIn($schema->getFile(), $hint->getOffset());
        }
    }

    /**
     * @param string $field
     * @param SchemaFieldDefinitionNode $rule
     * @param SchemaDefinition $schema
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function validateFieldName(string $field, SchemaFieldDefinitionNode $rule, SchemaDefinition $schema): void
    {
        if (! \in_array($field, [self::FIELD_QUERY, self::FIELD_MUTATION, self::FIELD_SUBSCRIPTION], true)) {
            $error = \sprintf('Invalid %s schema field name "%s"', $schema, $field);

            throw (new TypeConflictException($error))->throwsIn($schema->getFile(), $rule->getOffset());
        }
    }
}
