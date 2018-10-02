<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\SymbolTable;

use Railt\SDL\IR\Type\TypeInterface;

/**
 * Interface VarSymbolInterface
 */
interface VarSymbolInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return TypeInterface|null
     */
    public function getType(): ?TypeInterface;

    /**
     * @return null|ValueInterface
     */
    public function getValue(): ?ValueInterface;

    /**
     * @param null|ValueInterface $value
     * @return VarSymbolInterface
     */
    public function set(?ValueInterface $value): self;

    /**
     * @return VarSymbolInterface
     */
    public function lock(): self;

    /**
     * @return VarSymbolInterface
     */
    public function unlock(): self;

    /**
     * @return bool
     */
    public function isConstant(): bool;
}
