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
use Railt\Reflection\Invocation\InputInvocation;
use Railt\SDL\Frontend\AST\ProvidesType;

/**
 * Class InputInvocationNode
 */
class InputInvocationNode extends Rule implements ProvidesType
{
    /**
     * @param Definition $context
     * @return Definition
     */
    public function resolve(Definition $context): Definition
    {
        return new InputInvocation($context->getDocument());
    }
}
