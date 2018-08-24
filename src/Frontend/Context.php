<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Railt\SDL\Frontend\IR\JoinableOpcode;

/**
 * Class Context
 */
class Context
{
    /**
     * @var JoinableOpcode[]
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
    }

    /**
     * @return null|JoinableOpcode
     */
    public function current(): ?JoinableOpcode
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
     * @return null|JoinableOpcode
     */
    public function create(): ?JoinableOpcode
    {
        $this->await = true;

        return $this->current();
    }

    /**
     * @return null|JoinableOpcode
     */
    public function close(): ?JoinableOpcode
    {
        $this->await = false;

        return $this->stack->pop();
    }

    /**
     * @param JoinableOpcode $opcode
     * @return JoinableOpcode
     */
    public function match(JoinableOpcode $opcode): JoinableOpcode
    {
        if ($this->await) {
            $this->stack->push($opcode);
            $this->await = false;
        }

        return $opcode;
    }
}
