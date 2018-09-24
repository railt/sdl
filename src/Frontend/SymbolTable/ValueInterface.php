<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\SymbolTable;

use Railt\SDL\Frontend\Type\TypeInterface;

/**
 * Interface ValueInterface
 */
interface ValueInterface
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;
}
