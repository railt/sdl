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
use Railt\SDL\Frontend\AST\Definition\Provider\TypeNameProvider;

/**
 * Class DirectiveValueNode
 */
class DirectiveValueNode extends Rule
{
    use TypeNameProvider;

    /**
     * @return iterable|ArgumentValueNode[]
     */
    public function getArgumentNodes(): iterable
    {
        yield from $this->find('ArgumentValue', 1);
    }
}
