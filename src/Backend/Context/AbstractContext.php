<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\Reflection\Contracts\Definition\TypeDefinition;

/**
 * Class AbstractContext
 */
abstract class AbstractContext implements ContextInterface
{
    /**
     * @var array|LocalContext[]
     */
    protected $types = [];

    /**
     * @param TypeDefinition $type
     * @return ContextInterface|LocalContext
     */
    public function create(TypeDefinition $type): ContextInterface
    {
        \assert($this instanceof LocalContextInterface);

        return $this->types[$type->getName()] = new LocalContext($this, $type);
    }

    /**
     * @param string $type
     * @return bool
     */
    protected function has(string $type): bool
    {
        return isset($this->types[$type]);
    }

    /**
     * @return string
     */
    abstract public function __toString(): string;
}
