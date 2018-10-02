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
 * Interface ValueInterface
 */
interface ValueInterface
{
    /**
     * @return int|string|null|float|bool|PrimitiveInterface
     */
    public function getValue();

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;
}
