<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Common;

use Railt\Reflection\Contracts\Definition\ScalarDefinition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\Dictionary;
use Railt\SDL\Exception\TypeConflictException;

/**
 * The class serves to dynamically output a type from values
 */
class ValueTypeResolver
{
    /**
     * @var callable[]
     */
    private const DEFAULT_BREAKPOINTS = [
        'String'  => '\\is_string',
        'Boolean' => '\\is_bool',
        'Float'   => '\\is_float',
        'Int'     => '\\is_int',
    ];

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var callable[]
     */
    private $breakpoints = self::DEFAULT_BREAKPOINTS;

    /**
     * ValueTypeResolver constructor.
     * @param Dictionary $dictionary
     */
    public function __construct(Dictionary $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * @param string $type
     * @param callable $filter
     * @return ValueTypeResolver
     */
    public function breakpoint(string $type, callable $filter): self
    {
        $this->breakpoints[$type] = $filter;

        return $this;
    }

    /**
     * @param string $type
     * @return TypeDefinition
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    private function load(string $type): TypeDefinition
    {
        return $this->dictionary->get($type);
    }

    /**
     * @param TypeDefinition $type
     * @param mixed $value
     * @param string $renderedValue
     * @return mixed
     * @throws TypeConflictException
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    public function castTo(TypeDefinition $type, $value, string $renderedValue = null)
    {
        foreach ($this->resolveType($value) as $haystack) {
            if ($haystack->instanceOf($type)) {
                if ($type instanceof ScalarDefinition) {
                    return $type->parse($value);
                }

                return $value;
            }
        }

        $error = 'Could not cast %s to %s';
        $error = \sprintf($error, $renderedValue, $type);

        throw new TypeConflictException($error);
    }

    /**
     * @param mixed $value
     * @return iterable|TypeDefinition[]
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    public function resolveType($value): iterable
    {
        foreach ($this->breakpoints as $name => $filter) {
            if ($filter($value)) {
                $inheritance = $this->getFilteredChildrenInheritance($this->load($name), $this->getFilter());

                foreach ($inheritance as $child) {
                    yield $child;
                }
            }
        }

        yield $this->load('Any');
    }

    /**
     * @return \Closure
     */
    private function getFilter(): \Closure
    {
        return function (TypeDefinition $resolved): bool {
            return $this->shouldBreak($resolved);
        };
    }

    /**
     * @param TypeDefinition $resolved
     * @return bool
     */
    private function shouldBreak(TypeDefinition $resolved): bool
    {
        return ! isset($this->breakpoints[$resolved->getName()]);
    }

    /**
     * @param TypeDefinition $type
     * @param \Closure $filter
     * @return \Generator|TypeDefinition[]
     */
    private function getFilteredChildrenInheritance(TypeDefinition $type, \Closure $filter): \Traversable
    {
        yield $type;

        foreach ($type->getChildrenInheritance() as $child) {
            if (! $filter($child)) {
                continue;
            }

            yield from $this->getFilteredChildrenInheritance($child, $filter);
        }
    }
}
