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
use Railt\SDL\Stack\CallStack;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class GlobalContext
 */
class GlobalContext extends Context implements GlobalContextInterface
{
    /**
     * @var \SplStack|LocalContextInterface[]
     */
    private $pool;

    /**
     * @var CallStackInterface|CallStack
     */
    private $stack;

    /**
     * GlobalContext constructor.
     * @param CallStackInterface $stack
     */
    public function __construct(CallStackInterface $stack)
    {
        $this->pool  = new \SplStack();
        $this->stack = $stack;
    }

    /**
     * @return CallStackInterface
     */
    public function getCallStack(): CallStackInterface
    {
        return $this->stack;
    }

    /**
     * @param Readable $file
     * @return LocalContextInterface
     */
    public function create(Readable $file = null): LocalContextInterface
    {
        \assert($file !== null, 'Could not create a new context from global without file');

        $ctx = new LocalContext($this, $file);

        $this->pool->push($ctx);

        return $ctx;
    }

    /**
     * @return LocalContextInterface
     */
    public function current(): LocalContextInterface
    {
        \assert($this->pool->count() > 0, 'Internal Error: Empty Context Stack');

        return $this->pool->top();
    }

    /**
     * @return LocalContextInterface
     */
    public function complete(): LocalContextInterface
    {
        \assert($this->pool->count() > 0, 'Internal Error: Empty Context Stack');

        return $this->pool->pop();
    }

    /**
     * @return \Traversable|LocalContextInterface[]
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->pool as $ctx) {
            yield $ctx;
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->pool->count();
    }
}
