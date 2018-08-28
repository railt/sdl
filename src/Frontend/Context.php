<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Railt\SDL\Frontend\IR\Opcode;

/**
 * Class Context
 */
class Context
{
    /**
     * @var Opcode[]
     */
    private $stack;

    /**
     * @var bool
     */
    private $await = false;

    /**
     * Context constructor.
     */
    public function __construct()
    {
        $this->stack = new \SplStack();
        $this->create();
    }

    /**
     * @return null|Opcode
     */
    public function current(): ?Opcode
    {
        return $this->stack->count() ? $this->stack->top() : null;
    }

    /**
     * @return null|Opcode
     */
    public function create(): ?Opcode
    {
        $this->await = true;

        return $this->current();
    }

    /**
     * @return null|Opcode
     */
    public function close(): ?Opcode
    {
        $this->await = false;

        return $this->stack->pop();
    }

    /**
     * @param Opcode $opcode
     * @return Opcode
     */
    public function match(Opcode $opcode): Opcode
    {
        if ($this->await) {
            $this->stack->push($opcode);
            $this->await = false;
        }

        return $opcode;
    }
}
