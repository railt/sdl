<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Context;

use Railt\Io\Readable;
use Railt\SDL\Exception\LossOfStackException;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Pool
 */
class Pool
{
    /**
     * @var \SplStack|ContextInterface[]
     */
    private $pool;

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * Pool constructor.
     * @param Readable $file
     * @param CallStackInterface $stack
     */
    public function __construct(Readable $file, CallStackInterface $stack)
    {
        $this->pool = new \SplStack();
        $this->stack = $stack;

        $this->push(new Context($file, $stack, $this));
    }

    /**
     * @param ContextInterface $context
     * @return ContextInterface
     */
    public function push(ContextInterface $context): ContextInterface
    {
        $this->pool->push($context);

        return $context;
    }

    /**
     * @return ContextInterface
     * @throws LossOfStackException
     */
    public function pop(): ContextInterface
    {
        if ($this->isEmpty()) {
            throw new LossOfStackException('Context stack overflow', $this->stack);
        }

        return $this->pool->pop();
    }

    /**
     * @return ContextInterface
     */
    public function current(): ContextInterface
    {
        return $this->pool->top();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->pool->count() <= 1;
    }
}
