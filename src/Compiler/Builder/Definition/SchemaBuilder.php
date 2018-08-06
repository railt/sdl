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
use Railt\SDL\Compiler\Renderer;
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

        foreach ($rule->getDirectives() as $ast) {
            $schema->withDirective($this->dependent($ast, $schema));
        }

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

            switch ($name) {
                case self::FIELD_QUERY:
                    $schema->withQuery($hint->getTypeName());
                    break;

                case self::FIELD_MUTATION:
                    $schema->withMutation($hint->getTypeName());
                    break;

                case self::FIELD_SUBSCRIPTION:
                    $schema->withSubscription($hint->getTypeName());
                    break;
            }
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
            $error = 'Schema field "%s" should be a nullable and non-list type, but "%s" given';
            $indication = Renderer::typeIndication($hint->getTypeName(), $hint->getModifiers());

            throw (new TypeConflictException(\sprintf($error, $field, $indication)))->throwsIn($schema->getFile(),
                $hint->getOffset());
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
            $error = \sprintf('Invalid %s field name "%s"', $schema, $field);

            throw (new TypeConflictException($error))->throwsIn($schema->getFile(), $rule->getOffset());
        }
    }
}
