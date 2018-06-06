<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Support;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Context\ProvidesContext;

/**
 * Trait AstFinder
 */
trait AstFinder
{
    /**
     * @param RuleInterface $root
     * @param string $name
     * @param \Closure $then
     * @param null $default
     * @return mixed|null
     */
    protected function ast(RuleInterface $root, string $name, \Closure $then, $default = null)
    {
        $ast = $root->find($name, 0);

        if ($ast && $this instanceof ProvidesContext) {
            $stack = $this->getContext()->getCallStack();

            $stack->pushAst($this->getContext()->getFile(), $ast);
            $result = $then($ast, $this->getContext());
            $stack->pop();

            return $result;
        }

        if ($ast) {
            return $then($ast);
        }

        return $default;
    }
}
