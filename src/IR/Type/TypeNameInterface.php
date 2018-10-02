<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type;

/**
 * Interface TypeNameInterface
 */
interface TypeNameInterface extends \IteratorAggregate
{
    /**
     * @var string
     */
    public const NAMESPACE_SEPARATOR = '/';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getFullyQualifiedName(): string;

    /**
     * @param string|iterable|TypeNameInterface|null $prefix
     * @return TypeNameInterface|self
     */
    public function in($prefix): self;

    /**
     * @param string|iterable|TypeNameInterface|null $suffix
     * @return TypeNameInterface
     */
    public function append($suffix): self;

    /**
     * @return bool
     */
    public function isGlobal(): bool;

    /**
     * @return TypeNameInterface
     */
    public function lock(): self;

    /**
     * @return TypeNameInterface
     */
    public function unlock(): self;

    /**
     * @return array|string[]
     */
    public function getChunks(): array;

    /**
     * @param string|iterable|TypeNameInterface|null $name
     * @return bool
     */
    public function is($name): bool;
}
