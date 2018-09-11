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
use Railt\SDL\Frontend\AST\Definition\ArgumentDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class ArgumentBuilder
 */
class ArgumentBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|ArgumentDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $argument = new TypeDefinition($ast->getFullName());
        $argument->in($file, $ast->getOffset());

        $argument->type = Type::of(Type::ARGUMENT);
        $argument->description = $ast->getDescription();

        $argument->modifiers = $ast->getHintModifiers();
        $argument->hint = $ast->getHintTypeName();

        $argument->default = yield $ast->getDefaultValue();

        yield from $this->loadDirectives($ast, $argument);

        return $argument;
    }
}
