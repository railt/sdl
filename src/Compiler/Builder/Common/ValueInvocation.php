<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Common;

use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\InputDefinition;
use Railt\Reflection\Contracts\Invocation\InputInvocation;
use Railt\Reflection\Contracts\Invocation\TypeInvocation;
use Railt\SDL\Exception\SemanticException;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class ValueInvocation
 */
class ValueInvocation
{
    /**
     * @var \SplQueue
     */
    private $stack;

    /**
     * ValueInvocation constructor.
     */
    public function __construct()
    {
        $this->stack = new \SplQueue();
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \InvalidArgumentException
     */
    public function invoke($value)
    {
        if ($value instanceof Definition) {
            $this->checkRecursiveReferring($value);
            $this->push($value);
        }

        if ($value instanceof InputInvocation) {
            return $this->invokeInput($value);
        }

        return $value;
    }

    /**
     * @param Definition $value
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function checkRecursiveReferring(Definition $value): void
    {
        if ($this->has($value)) {
            $error = 'Recursive reference (%s -> %s) detected while %s invocation';
            $error = \sprintf($error, $this->getStackAsString(), $value, $value);

            throw (new SemanticException($error))->throwsIn($value->getFile(), $value->getLine(), $value->getColumn());
        }
    }

    /**
     * @param Definition $definition
     * @return bool
     */
    private function has(Definition $definition): bool
    {
        $stack = clone $this->stack;

        foreach ($stack as $i) {
            if ((string)$i === (string)$definition) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    private function getStackAsString(): string
    {
        return \implode(' -> ', \iterator_to_array(clone $this->stack));
    }

    /**
     * @param Definition $definition
     */
    private function push(Definition $definition): void
    {
        $this->stack->push($definition);
    }

    /**
     * @param InputInvocation $input
     * @return InputInvocation
     * @throws \InvalidArgumentException
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function invokeInput(InputInvocation $input): InputInvocation
    {
        /** @var InputDefinition $definition */
        $definition = $input->getDefinition();

        foreach ($definition->getFields() as $field) {
            $argument = $input->getArgument($field->getName());

            if ($argument === null && $field->isNonNull() && ! $field->hasDefaultValue()) {
                $error = \sprintf('Missing value for required argument %s', $field);
                throw (new TypeConflictException($error))->throwsIn($input->getFile(), $input->getLine(),
                        $input->getColumn());
            }

            $this->invoke($argument);
        }

        return $input;
    }
}
