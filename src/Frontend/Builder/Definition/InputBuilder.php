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
use Railt\SDL\Frontend\Ast\Definition\InputDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class InputBuilder
 */
class InputBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|InputDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $input = new TypeDefinition($ast->getFullName());
        $input->in($file, $ast->getOffset());

        $input->type        = Type::INPUT_OBJECT;
        $input->description = $ast->getDescription();

        yield from $this->loadInputFields($ast, $input);

        return $input;
    }

    /**
     * @param InputDefinitionNode $ast
     * @param TypeDefinition $input
     * @return \Generator
     */
    protected function loadInputFields(InputDefinitionNode $ast, TypeDefinition $input): \Generator
    {
        $input->fields = [];

        foreach ($ast->getInputFieldNodes() as $field) {
            $input->fields[] = yield $field;
        }
    }
}
