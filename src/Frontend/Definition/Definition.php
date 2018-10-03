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
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class Definition
 */
class Definition implements DefinitionInterface
{
    /**
     * @var TypeNameInterface
     */
    private $name;

    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * Definition constructor.
     * @param ContextInterface $context
     * @param TypeNameInterface $name
     */
    public function __construct(ContextInterface $context, TypeNameInterface $name)
    {
        $this->context = $context;
        $this->name = $name->in($context->getName());
    }

    /**
     * @return iterable|DefinitionArgumentInterface[]
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @param TypeNameInterface $hint
     * @return DefinitionArgumentInterface
     */
    public function addArgument(string $name, TypeNameInterface $hint): DefinitionArgumentInterface
    {
        return $this->arguments[$name] = new Argument($name, $hint);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return isset($this->arguments[$name]);
    }

    /**
     * @param string $name
     * @return DefinitionArgumentInterface
     */
    public function getArgument(string $name): DefinitionArgumentInterface
    {
        return $this->arguments[$name];
    }

    /**
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface
    {
        return $this->name;
    }

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface
    {
        return $this->context;
    }

    /**
     * @return bool
     */
    public function isGeneric(): bool
    {
        return \count($this->arguments) > 0;
    }
}
