<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\SymbolTable;

use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class TypeNamePrimitive
 */
class TypeNamePrimitive implements PrimitiveInterface
{
    /**
     * @var TypeNameInterface
     */
    private $name;

    /**
     * TypeNamePrimitive constructor.
     * @param TypeNameInterface $name
     */
    public function __construct(TypeNameInterface $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $prefix = $this->name->isGlobal() ? TypeNameInterface::NAMESPACE_SEPARATOR : '';

        return $prefix . $this->name->getFullyQualifiedName();
    }
}
