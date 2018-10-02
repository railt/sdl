<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Naming;

use Railt\SDL\Frontend\Invocation\ArgumentInterface;
use Railt\SDL\Frontend\Invocation\TypeInvocation;
use Railt\SDL\IR\TypeNameInterface;

/**
 * Class SimpleNamingStrategy
 */
class SimpleNamingStrategy extends Strategy
{
    /**
     * PrettyNamingStrategy constructor.
     */
    public function __construct()
    {
        parent::__construct(\Closure::fromCallable([$this, 'format']));
    }

    /**
     * @param TypeNameInterface $name
     * @param iterable|ArgumentInterface[] $arguments
     * @return string
     */
    protected function format(TypeNameInterface $name, iterable $arguments): string
    {
        return $this->formatName($name) . $this->formatArguments($arguments);
    }

    /**
     * @param iterable|ArgumentInterface[] $arguments
     * @return string
     */
    protected function formatArguments(iterable $arguments): string
    {
        $result = [];

        foreach ($arguments as $argument) {
            $result[] = $this->formatArgument($argument);
        }

        if (\count($result)) {
            return 'Of' . \implode('And', $result);
        }

        return '';
    }

    /**
     * @param ArgumentInterface $argument
     * @return string
     */
    protected function formatArgument(ArgumentInterface $argument): string
    {
        $value  = $argument->getValue();
        $suffix = $value instanceof TypeInvocation ? $this->formatType($value) : $this->formatArgument($value);

        return \ucfirst($argument->getName()) . $suffix;
    }

    /**
     * @param TypeInvocation $type
     * @return string
     */
    protected function formatType(TypeInvocation $type): string
    {
        return $this->formatName($type->getTypeName()) . $this->formatArguments($type->getArguments());
    }

    /**
     * @param TypeNameInterface $name
     * @return string
     */
    protected function formatName(TypeNameInterface $name): string
    {
        $from = TypeNameInterface::NAMESPACE_SEPARATOR;

        return \str_replace($from, '_', $name->getFullyQualifiedName());
    }
}
