<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Lexer\Exception\LexerException;
use Railt\Parser\Exception\ParserException;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Exception\ReflectionException;
use Railt\Reflection\Exception\TypeNotFoundException as TypeNotFoundReflectionException;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\SemanticException;
use Railt\SDL\Exception\SyntaxException;
use Railt\SDL\Exception\TypeNotFoundException;

/**
 * Class CallStack
 */
class CallStack implements \IteratorAggregate, \Countable
{
    /**
     * @var \SplStack
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
     * @return int
     */
    public function count(): int
    {
        return $this->stack->count();
    }

    /**
     * @param Definition $definition
     * @return CallStack
     */
    public function push(Definition $definition): self
    {
        $this->stack->push($definition);

        return $this;
    }

    /**
     * @return CallStack
     */
    public function pop(): self
    {
        $this->stack->pop();

        return $this;
    }

    /**
     * @return \Traversable|Definition[]
     */
    public function getIterator(): \Traversable
    {
        $copy = clone $this->stack;

        while ($copy->count() > 0) {
            yield $copy->pop();
        }
    }
}
