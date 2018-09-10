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
use Railt\SDL\Frontend\Ast\Definition\ScalarDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class ScalarBuilder
 */
class ScalarBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|ScalarDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $scalar = new TypeDefinition($ast->getFullName());
        $scalar->in($file, $ast->getOffset());

        $scalar->type = Type::SCALAR;
        $scalar->description = $ast->getDescription();

        $scalar->extends = $ast->getExtends();

        return $scalar;
    }
}
