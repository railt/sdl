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
 * Class Type
 */
class Type implements TypeInterface, \JsonSerializable
{
    /**
     * @var TypeNameInterface
     */
    private $type;

    /**
     * @var array
     */
    private static $inheritance = [];

    /**
     * @var array|string[]
     */
    private $parent;

    /**
     * Type constructor.
     * @param TypeNameInterface $type
     */
    private function __construct(TypeNameInterface $type)
    {
        $this->type = $type;
        $this->parent = $this->getInheritanceSequence($type->getFullyQualifiedName());
    }

    /**
     * @param string $name
     * @return array
     */
    private function getInheritanceSequence(string $name): array
    {
        if (self::$inheritance === []) {
            $this->bootInheritance(new \SplStack(), static::INHERITANCE_TREE);
        }

        return self::$inheritance[$name] ?? [static::ROOT_TYPE];
    }

    /**
     * @param \SplStack $stack
     * @param array $children
     */
    private function bootInheritance(\SplStack $stack, array $children = []): void
    {
        $push = function (string $type) use ($stack): void {
            self::$inheritance[$type]   = \array_values(\iterator_to_array($stack));
            self::$inheritance[$type][] = static::ROOT_TYPE;

            $stack->push($type);
        };

        foreach ($children as $type => $child) {
            switch (true) {
                case \is_string($child):
                    $push($child);
                    break;

                case \is_array($child):
                    $push($type);
                    $this->bootInheritance($stack, $child);
                    break;
            }

            $stack->pop();
        }
    }

    /**
     * @param TypeNameInterface $name
     * @return TypeInterface
     */
    public static function of(TypeNameInterface $name): TypeInterface
    {
        return new static($name);
    }

    /**
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function is(TypeInterface $type): bool
    {
        return $this->getName() === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function instanceOf(TypeInterface $type): bool
    {
        $needle = $type->getName()->getFullyQualifiedName();

        return $this->is($type) || \in_array($needle, $this->parent, true);
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->getName()->getFullyQualifiedName();
    }
}
