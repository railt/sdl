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
use Railt\SDL\Frontend\Ast\Definition\EnumDefinitionNode;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\TypeDefinition;

/**
 * Class EnumValueBuilder
 */
class EnumValueBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param RuleInterface|EnumDefinitionNode $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        $value = new TypeDefinition($ast->getFullName());
        $value->in($file, $ast->getOffset());

        $value->type        = Type::ENUM_VALUE;
        $value->description = $ast->getDescription();

        return $value;
    }
}
