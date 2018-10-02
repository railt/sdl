<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

use Railt\SDL\IR\Type\Name;
use Railt\SDL\IR\Type\TypeConstructors;
use Railt\SDL\IR\Type\InternalTypes;
use Railt\SDL\IR\Type\TypeInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class Type
 */
class Type implements TypeInterface
{
    use InternalTypes;
    use TypeConstructors;

    /**
     * @var bool
     */
    private static $booted = false;

    /**
     * @var TypeNameInterface
     */
    protected $type;

    /**
     * @var TypeInterface
     */
    protected $of;

    /**
     * @var bool|null
     */
    private $inputable;

    /**
     * @var bool|null
     */
    private $returnable;

    /**
     * @var string
     */
    private $hash;

    /**
     * Type constructor.
     * @param string|iterable|TypeNameInterface $name
     * @param TypeInterface|null $of
     */
    protected function __construct($name, TypeInterface $of = null)
    {
        $this->type = Name::new($name);
        $this->of = $this->resolveTypeOf($this->type, $of);
    }

    /**
     * @param TypeNameInterface $type
     * @param null|TypeInterface $of
     * @return TypeInterface|static
     */
    private function resolveTypeOf(TypeNameInterface $type, ?TypeInterface $of): TypeInterface
    {
        if ($of === null) {
            $any = Name::new(self::ROOT_TYPE);

            return $type->is($any) ? $this : self::new($any);
        }

        return $of;
    }

    /**
     * @return void
     */
    private static function bootIfNotBooted(): void
    {
        if (self::$booted === false) {
            self::$booted = true;

            self::bootInternalTypes();
        }
    }

    /**
     * @param string|iterable|TypeNameInterface $name
     * @param TypeInterface|null $of
     * @return TypeInterface|static
     */
    public static function new($name, TypeInterface $of = null): TypeInterface
    {
        self::bootIfNotBooted();

        $fqn = Name::new($name)->getFullyQualifiedName();

        return self::getInternalType($fqn, $of, function () use ($name, $of): TypeInterface {
            return new static($name, $of);
        });
    }

    /**
     * @param TypeInterface $of
     * @return TypeInterface|static
     */
    public function of(TypeInterface $of): TypeInterface
    {
        \assert(! $this->isInternal(), 'Can not change inheritance logic of internal types');

        return static::new($this->type, $of);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): TypeInterface
    {
        return $this->of;
    }

    /**
     * {@inheritdoc}
     */
    public function typeOf(TypeInterface $type): bool
    {
        foreach ($this->getInheritanceSequence($this) as $parent) {
            if ($parent->is($type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TypeInterface $type
     * @return \Generator|TypeInterface[]
     */
    private function getInheritanceSequence(TypeInterface $type): \Generator
    {
        yield $type;

        if (! $type->getName()->is(static::ROOT_TYPE)) {
            yield from $this->getInheritanceSequence($type->getParent());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isInputable(): bool
    {
        if ($this->inputable === null) {
            if ($this->isBuiltin() && ! \in_array($this->fqn(), static::ALLOWS_TO_INPUT, true)) {
                return $this->inputable = false;
            }

            if ($this->is(self::any())) {
                return $this->inputable = true;
            }

            $this->inputable = $this->of->isInputable();
        }

        return $this->inputable;
    }

    /**
     * {@inheritdoc}
     */
    public function isBuiltin(): bool
    {
        $fqn = $this->fqn();

        return \in_array($fqn, static::INDEPENDENT_TYPES, true)
             || \in_array($fqn, static::WRAPPING_TYPES, true)
             || \in_array($fqn, static::DEPENDENT_TYPES, true)
        ;
    }

    /**
     * @return string
     */
    private function fqn(): string
    {
        return $this->type->getFullyQualifiedName();
    }

    /**
     * {@inheritdoc}
     */
    public function is(TypeInterface $is): bool
    {
        return $is->getHash() === $this->getHash();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): TypeNameInterface
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function isReturnable(): bool
    {
        if ($this->returnable === null) {
            if ($this->isBuiltin() && ! \in_array($this->fqn(), static::ALLOWS_TO_OUTPUT, true)) {
                return $this->returnable = false;
            }

            if ($this->is(self::any())) {
                return $this->returnable = true;
            }

            $this->returnable = $this->of->isReturnable();
        }

        return $this->returnable;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'type' => $this->fqn(),
            'of'   => $this->of,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->isInternal()) {
            return $this->fqn();
        }

        return \sprintf('%s<%s>', $this->fqn(), $this->of);
    }

    /**
     * @return bool
     */
    private function isInternal(): bool
    {
        return $this->isBuiltin() || \in_array($this->fqn(), self::RUNTIME_TYPES, true);
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        if ($this->hash === null) {
            $this->hash = $this->fqn() === self::ROOT_TYPE
                ? \sha1($this->fqn())
                : \sha1($this->fqn() . ':' . $this->of->getHash());
        }

        return $this->hash;
    }
}
