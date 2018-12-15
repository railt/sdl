<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\SchemaDefinition;
use Railt\Reflection\Type;
use Railt\SDL\Builder\Utils;
use Railt\SDL\Exception\InternalErrorException;
use Railt\SDL\Exception\TypeException;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder extends TypeDefinitionBuilder
{
    /**
     * @return Definition
     */
    public function build(): Definition
    {
        /** @var SchemaDefinition $schema */
        $schema = $this->bind(new SchemaDefinition($this->document, $this->findName()));

        foreach ($this->ast as $child) {
            $this->async(function () use ($child, $schema): void {
                $this->buildField($child, $schema);
            });
        }

        return $schema;
    }

    /**
     * @param RuleInterface $rule
     * @param SchemaDefinition $definition
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws InternalErrorException
     */
    private function buildField(RuleInterface $rule, SchemaDefinition $definition): void
    {
        $name = $this->getFieldName($rule);
        $type = $this->getFieldType($rule, $definition, $name);

        switch ($name) {
            case 'query':
                $definition->withQuery($type);
                break;

            case 'mutation':
                $definition->withMutation($type);
                break;

            case 'subscription':
                $definition->withSubscription($type);
                break;
        }
    }

    /**
     * @param RuleInterface $rule
     * @return string
     * @throws InternalErrorException
     */
    private function getFieldName(RuleInterface $rule): string
    {
        $type = Utils::leaf($rule, 'SchemaFieldType');

        if ($type === null) {
            throw new InternalErrorException('Rule SchemaFieldType not found');
        }

        return $type;
    }

    /**
     * @param RuleInterface $rule
     * @param SchemaDefinition $definition
     * @param string $field
     * @return Definition
     * @throws InternalErrorException
     * @throws TypeException
     * @throws \Railt\SDL\Exception\TypeNotFoundException
     */
    private function getFieldType(RuleInterface $rule, SchemaDefinition $definition, string $field): Definition
    {
        if (($type = Utils::rule($rule, 'Type')) === null) {
            throw new InternalErrorException('Rule Type not found');
        }

        if (($name = Utils::findName($type)) === null) {
            $error = \sprintf('Schema %s field type can not be List or NonNull', $field);

            $exception = new TypeException($error);
            $exception->throwsIn($this->file, $type->getOffset());

            throw $exception;
        }

        $result = $this->load($name, $definition, $type->getOffset());

        if (! Type::of($result::getType())->is(Type::OBJECT)) {
            $error = 'Schema %s field type should be an %s, but %s given';
            $error = \sprintf($error, $field, Type::OBJECT, $result);

            $exception = new TypeException($error);
            $exception->throwsIn($this->file, $type->getOffset());

            throw $exception;
        }

        return $result;
    }
}
