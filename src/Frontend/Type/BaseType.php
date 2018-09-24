<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Type;

/**
 * Class BaseType
 */
abstract class BaseType implements TypeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var TypeInterface
     */
    private $parent;

    /**
     * BaseType constructor.
     * @param string $name
     * @param TypeInterface $parent
     */
    public function __construct(string $name, TypeInterface $parent)
    {
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function is(TypeInterface $type): bool
    {
        return $type->getName() === $this->getName();
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function instanceOf(TypeInterface $type): bool
    {
        return $this->is($type) || $this->parent->instanceOf($type);
    }
}
