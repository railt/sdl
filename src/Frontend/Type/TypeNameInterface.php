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
 * Interface TypeNameInterface
 */
interface TypeNameInterface extends \IteratorAggregate
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getFullyQualifiedName(): string;

    /**
     * @param TypeNameInterface $prefix
     * @return TypeNameInterface
     */
    public function in(TypeNameInterface $prefix): TypeNameInterface;

    /**
     * @return array|string[]
     */
    public function getChunks(): array;

    /**
     * @param TypeNameInterface $name
     * @return bool
     */
    public function is(TypeNameInterface $name): bool;
}
