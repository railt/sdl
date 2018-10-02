<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\SymbolTable;

use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class TypeInvocationPrimitive
 */
class TypeInvocationPrimitive implements PrimitiveInterface
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
     * TypeInvocation constructor.
     * @param TypeNameInterface $name
     * @param ContextInterface $context
     */
    public function __construct(TypeNameInterface $name, ContextInterface $context)
    {
        $this->name    = $name;
        $this->context = $context;
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
     * @return string
     */
    public function __toString(): string
    {
        return $this->name->getFullyQualifiedName();
    }
}
