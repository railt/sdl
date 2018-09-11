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
use Railt\SDL\Frontend\AST\Definition\InputFieldDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class InputFieldBuilder
 */
class InputFieldBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|InputFieldDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $field = new TypeDefinition($ast->getFullName());
        $field->in($file, $ast->getOffset());

        $field->type = Type::of(Type::INPUT_FIELD_DEFINITION);
        $field->description = $ast->getDescription();

        $field->modifiers = $ast->getHintModifiers();
        $field->hint = $ast->getHintTypeName();

        $field->default = yield $ast->getDefaultValue();

        yield from $this->loadDirectives($ast, $field);

        return $field;
    }
}
