<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Context;

use Railt\Io\Readable;
use Railt\SDL\Frontend\Type\TypeName;
use Railt\SDL\Frontend\Type\TypeNameInterface;

/**
 * Class Context
 */
class Context implements ContextInterface, \Countable
{
    /**
     * @var TypeNameInterface
     */
    private $current;

    /**
     * @var \SplStack|TypeNameInterface[]
     */
    private $stack;

    /**
     * @var Readable
     */
    private $file;

    /**
     * Context constructor.
     * @param Readable $file
     */
    public function __construct(Readable $file)
    {
        $this->file = $file;
        $this->stack = new \SplStack();
        $this->current = TypeName::global();
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    /**
     * @return TypeNameInterface
     */
    public function current(): TypeNameInterface
    {
        return $this->current;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->stack->count();
    }

    /**
     * @param TypeNameInterface $name
     * @return TypeNameInterface
     */
    public function create(TypeNameInterface $name): TypeNameInterface
    {
        $this->current = $name->in($this->current);

        $this->stack->push($this->current);

        return $this->current;
    }

    /**
     * @return TypeNameInterface
     */
    public function close(): TypeNameInterface
    {
        $current = $this->stack->pop();

        $this->current = $this->stack->count() ? $this->stack->top() : TypeName::global();

        return $current;
    }
}
