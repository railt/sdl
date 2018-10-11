<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Definition;

use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\SymbolTable\PrimitiveInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class InvocationPrimitive
 */
class Invocation implements InvocationInterface
{
    /**
     * @var TypeNameInterface
     */
    private $name;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var ContextInterface
     */
    private $from;

    /**
     * InvocationPrimitive constructor.
     * @param TypeNameInterface $name
     * @param ContextInterface $from
     */
    public function __construct(TypeNameInterface $name, ContextInterface $from)
    {
        $this->name = $name;
        $this->from = $from;
    }

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface
    {
        return $this->from;
    }

    /**
     * @return iterable|InvocationInterface[]
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @param Invocation|PrimitiveInterface $value
     * @return InvocationInterface
     */
    public function addArgument(string $name, $value): InvocationInterface
    {
        $this->arguments[$name] = $value;

        return $this;
    }

    /**
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->hasArguments()) {
            $arguments = [];

            foreach ($this->arguments as $name => $value) {
                $arguments[] = $name . ': ' . $value;
            }

            return \sprintf('%s<%s>', $this->name->getFullyQualifiedName(), \implode(', ', $arguments));
        }

        return $this->name->getFullyQualifiedName();
    }

    /**
     * @return bool
     */
    public function hasArguments(): bool
    {
        return \count($this->arguments) > 0;
    }
}
