<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Invocation;

use Railt\Parser\Ast\Rule;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Invocation\DirectiveInvocation;
use Railt\SDL\Frontend\AST\ProvidesType;
use Railt\SDL\Frontend\AST\ProvidesName;
use Railt\SDL\Frontend\AST\Support\TypeNameProvider;

/**
 * Class DirectiveInvocationNode
 */
class DirectiveInvocationNode extends Rule implements ProvidesType, ProvidesName
{
    use TypeNameProvider;

    /**
     * @param Definition $context
     * @return Definition
     */
    public function resolve(Definition $context): Definition
    {
        return new DirectiveInvocation($context->getDocument(), $this->getFullName());
    }
}
