<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Reflection\Definition\TypeDefinition;
use Railt\SDL\Dictionary;
use Railt\SDL\Exception\TypeNotFoundException;
use Railt\SDL\Exception\TypeRedefinitionException;
use Railt\SDL\Stack\CallStack;

/**
 * Class TypeDictionary
 */
class TypeDictionary implements Dictionary
{
    /**
     * @var array|TypeDefinition
     */
    private $types = [];

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * TypeDictionary constructor.
     * @param CallStack $stack
     */
    public function __construct(CallStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @param string $type
     * @return TypeDefinition
     * @throws TypeNotFoundException
     */
    public function get(string $type): TypeDefinition
    {
        if (! $this->has($type)) {
            $this->throwTypeNotFound($type);
        }

        return $this->types[$type];
    }

    /**
     * @param string $type
     * @return bool
     */
    public function has(string $type): bool
    {
        return \array_key_exists($type, $this->types);
    }

    /**
     * @param string $type
     * @throws TypeNotFoundException
     */
    private function throwTypeNotFound(string $type): void
    {
        $error = 'Type %s not found or could not be loaded';

        throw new TypeNotFoundException(\sprintf($error, $type), $this->stack);
    }

    /**
     * @param TypeDefinition $type
     * @throws TypeRedefinitionException
     */
    public function register(TypeDefinition $type): void
    {
        if ($this->has($type->getName())) {
            $this->throwTypeRedefinition($type);
        }

        $this->types[$type->getName()] = $type;
    }

    /**
     * @param TypeDefinition $type
     * @throws TypeRedefinitionException
     */
    private function throwTypeRedefinition(TypeDefinition $type): void
    {
        $error = 'Could not redeclare type %s, because type %s already registered';
        $error = \sprintf($error, $type, $this->types[$type->getName()]);

        throw new TypeRedefinitionException($error, $this->stack);
    }
}
