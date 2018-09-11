<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

/**
 * @method static static|TypeInterface|Type scalar()
 * @method static static|TypeInterface|Type object()
 * @method static static|TypeInterface|Type directive()
 * @method static static|TypeInterface|Type directiveLocation()
 * @method static static|TypeInterface|Type interface()
 * @method static static|TypeInterface|Type union()
 * @method static static|TypeInterface|Type enum()
 * @method static static|TypeInterface|Type input()
 * @method static static|TypeInterface|Type inputUnion()
 * @method static static|TypeInterface|Type schema()
 * @method static static|TypeInterface|Type enumValue()
 * @method static static|TypeInterface|Type field()
 * @method static static|TypeInterface|Type argument()
 * @method static static|TypeInterface|Type inputField()
 * @method static static|TypeInterface|Type document()
 * @method static static|TypeInterface|Type any()
 */
class Type implements TypeInterface
{
    /**
     * @var Type[]
     */
    private static $instances = [];

    /**
     * @var array[]|string[][]
     */
    private static $inheritance = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|string[]
     */
    private $parent;

    /**
     * BaseType constructor.
     * @param string $name
     */
    private function __construct(string $name)
    {
        $this->name   = $name;
        $this->parent = $this->getInheritanceSequence($name);
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

            /** @noinspection DisconnectedForeachInstructionInspection */
            $stack->pop();
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return TypeInterface
     */
    public static function __callStatic(string $name, array $arguments = [])
    {
        foreach (static::all() as $type) {
            if (\strtolower($type) === \strtolower($name)) {
                return static::of($type);
            }
        }

        return static::of(static::ANY);
    }

    /**
     * @param string $type
     * @return TypeInterface
     */
    public static function of($type): TypeInterface
    {
        switch (true) {
            case \is_string($type):
                return self::$instances[$type] ?? (self::$instances[$type] = new static($type));

            case $type instanceof TypeInterface:
                return $type;
        }

        return static::of(static::ANY);
    }

    /**
     * {@inheritDoc}
     */
    public function isInputable(): bool
    {
        return \in_array($this->name, static::ALLOWS_TO_INPUT, true);
    }

    /**
     * {@inheritDoc}
     */
    public function isReturnable(): bool
    {
        return \in_array($this->name, static::ALLOWS_TO_OUTPUT, true);
    }

    /**
     * @return bool
     */
    public function isIndependent(): bool
    {
        return \in_array($this->name, static::INDEPENDENT_TYPES, true);
    }

    /**
     * {@inheritDoc}
     */
    public function isDependent(): bool
    {
        return \in_array($this->name, static::DEPENDENT_TYPES, true);
    }

    /**
     * {@inheritDoc}
     */
    public function instanceOf(TypeInterface $type): bool
    {
        $needle = $type->getName();

        return $this->is($needle) || \in_array($needle, $this->parent, true);
    }

    /**
     * {@inheritDoc}
     */
    public function is(string $type): bool
    {
        return $this->getName() === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(): bool
    {
        return \in_array($this->name, static::all(), true);
    }

    /**
     * {@inheritDoc}
     */
    public function isInternal(): bool
    {
        return \in_array($this->name, static::INTERNAL_TYPES, true);
    }

    /**
     * {@inheritDoc}
     */
    public function isExtension(): bool
    {
        return \in_array($this->name, static::EXTENSION_TYPES, true);
    }

    /**
     * @return array
     */
    public static function all(): array
    {
        $types = [
            static::INDEPENDENT_TYPES,
            static::DEPENDENT_TYPES,
            static::INTERNAL_TYPES,
            static::EXTENSION_TYPES,
        ];

        return \array_merge(...$types);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
