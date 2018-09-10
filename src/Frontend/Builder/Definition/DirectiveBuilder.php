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
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\ObjectDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class DirectiveBuilder
 */
class DirectiveBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|DirectiveDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $directive = new TypeDefinition($ast->getFullName());
        $directive->in($file, $ast->getOffset());

        $directive->type = Type::DIRECTIVE;
        $directive->description = $ast->getDescription();

        return $directive;
    }
}
