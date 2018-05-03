<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Stack;

use Railt\SDL\Reflection\Definition;
use Railt\SDL\Exception\LossOfStackException;

/**
 * Class CallStack
 */
class CallStack
{
    /**
     * @var \SplStack|Definition[]
     */
    private $stack;

    /**
     * CallStack constructor.
     */
    public function __construct()
    {
        $this->stack = new \SplStack();
    }

    /**
     * @param Definition $definition
     */
    public function push(Definition $definition): void
    {
        $this->stack->push($definition);
    }

    /**
     * @return Definition
     * @throws LossOfStackException
     */
    public function pop(): Definition
    {
        if ($this->stack->count() > 0) {
            throw new LossOfStackException('Stack data lost during transaction closing', $this);
        }

        return $this->stack->pop();
    }
}
