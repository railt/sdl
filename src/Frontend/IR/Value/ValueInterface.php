<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR\Value;

use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\TypeInterface;

/**
 * Interface ValueInterface
 */
interface ValueInterface
{
    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @return TypeDefinition
     */
    public function getType(): TypeDefinition;

    /**
     * @param TypeDefinition $type
     * @return ValueInterface
     */
    public function bindTo(TypeDefinition $type): self;

    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function instanceOf(TypeDefinition $type): bool;

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function typeOf(TypeInterface $type): bool;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return string
     */
    public function __toString(): string;
}
