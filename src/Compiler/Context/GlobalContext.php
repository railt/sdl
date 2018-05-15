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
 * Class GlobalContext
 */
class GlobalContext extends Context implements GlobalContextInterface
{
    /**
     * @var \SplStack|LocalContextInterface[]
     */
    private $pool;

    /**
     * GlobalContext constructor.
     * @param CallStackInterface $stack
     */
    public function __construct(CallStackInterface $stack)
    {
        $this->pool = new \SplStack();

        parent::__construct($stack);
    }

    /**
     * @return ContextInterface
     */
    public function current(): ContextInterface
    {
        if ($this->pool->count()) {
            return $this->pool->top();
        }

        return $this;
    }

    /**
     * @return LocalContextInterface
     */
    public function pop(): LocalContextInterface
    {
        if ($this->isEmpty()) {
            throw new LossOfStackException('Context stack overflow', $this->getStack());
        }

        return $this->pool->pop();
    }

    /**
     * @param LocalContextInterface $context
     * @return LocalContextInterface
     */
    public function push(LocalContextInterface $context): LocalContextInterface
    {
        $this->pool->push($context);

        return $context;
    }

    /**
     * @param Readable $file
     * @param string|null $name
     * @return LocalContextInterface
     */
    public function create(Readable $file = null, string $name = null): LocalContextInterface
    {
        // TODO Can throws an exception if current context is global
        if ($file === null) {
            /** @var LocalContextInterface $context */
            $context = $this->current();

            if ($context instanceof GlobalContextInterface) {
                throw new \InvalidArgumentException('Bad context');
            }

            $file = $context->getFile();
        }

        return new LocalContext($this->getStack(), $file, $this, $name);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->pool->count() <= 0;
    }

    /**
     * @return bool
     */
    public function atRoot(): bool
    {
        return true;
    }
}
