<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Railt\SDL\Frontend\IR\JoinedOpcode;

/**
 * Class Context
 */
class Context
{
    /**
     * @var JoinedOpcode[]
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
     * @return null|JoinedOpcode
     */
    public function current(): ?JoinedOpcode
    {
        return $this->stack->count() ? $this->stack->top() : null;
    }

    /**
     * @param \Closure $then
     * @return \Generator
     */
    public function transaction(\Closure $then): \Generator
    {
        $current = $this->current();

        $result = $then($current);

        if ($current !== $this->current()) {
            $this->close();
        }

        return $result;
    }

    /**
     * @return null|JoinedOpcode
     */
    public function create(): ?JoinedOpcode
    {
        $this->await = true;

        return $this->current();
    }

    /**
     * @return null|JoinedOpcode
     */
    public function close(): ?JoinedOpcode
    {
        $this->await = false;

        return $this->stack->pop();
    }

    /**
     * @param JoinedOpcode $opcode
     * @return JoinedOpcode
     */
    public function match(JoinedOpcode $opcode): JoinedOpcode
    {
        if ($this->await) {
            $this->stack->push($opcode);
            $this->await = false;
        }

        return $opcode;
    }
}
