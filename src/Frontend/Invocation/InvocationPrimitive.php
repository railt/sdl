<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Invocation;

use Railt\SDL\IR\SymbolTable\PrimitiveInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class InvocationPrimitive
 */
class InvocationPrimitive implements InvocationInterface
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
     * InvocationPrimitive constructor.
     * @param TypeNameInterface $name
     */
    public function __construct(TypeNameInterface $name)
    {
        $this->name = $name;
    }

    /**
     * @return array|InvocationPrimitive[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @param InvocationPrimitive|PrimitiveInterface $value
     * @return InvocationPrimitive
     */
    public function addArgument(string $name, $value): InvocationPrimitive
    {
        $this->arguments[$name] = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasArguments(): bool
    {
        return \count($this->arguments) > 0;
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
}
