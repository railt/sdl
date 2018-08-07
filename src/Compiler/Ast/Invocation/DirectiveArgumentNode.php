<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Invocation;

use Railt\Parser\Ast\Rule;
use Railt\SDL\Compiler\Ast\Value\ValueInterface;

/**
 * Class DirectiveArgumentNode
 */
class DirectiveArgumentNode extends Rule
{
    /**
     * @return string
     */
    public function getArgumentName(): string
    {
        return $this->first('DirectiveArgumentName', 1)
            ->getChild(0)
            ->getValue();
    }

    /**
     * @return ValueInterface
     */
    public function getArgumentValue(): ValueInterface
    {
        return $this->first('Value', 1);
    }
}
