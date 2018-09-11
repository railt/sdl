<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Invocation;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Invocation\AstValueInterface;
use Railt\SDL\Frontend\Builder\DefinitionBuilder;

/**
 * Class ValueBuilder
 */
class ValueBuilder extends DefinitionBuilder
{
    /**
     * @param Readable $file
     * @param AstValueInterface|RuleInterface $ast
     * @return \Generator|mixed
     */
    public function build(Readable $file, RuleInterface $ast)
    {
        return $ast->toValue($file);
    }
}
