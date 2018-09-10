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
use Railt\SDL\Frontend\Ast\Definition\FieldDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class FieldBuilder
 */
class FieldBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|FieldDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $field = new TypeDefinition($ast->getFullName());
        $field->in($file, $ast->getOffset());

        $field->type = Type::FIELD;
        $field->description = $ast->getDescription();

        $field->modifiers = $ast->getHintModifiers();
        $field->hint = $ast->getHintTypeName();

        yield from $this->loadArguments($ast, $field);

        return $field;
    }

    /**
     * @param FieldDefinitionNode $ast
     * @param TypeDefinition $field
     * @return \Generator
     */
    protected function loadArguments(FieldDefinitionNode $ast, TypeDefinition $field): \Generator
    {
        $field->arguments = [];

        foreach ($ast->getArgumentNodes() as $argument) {
            $field->arguments[] = yield $argument;
        }
    }
}
