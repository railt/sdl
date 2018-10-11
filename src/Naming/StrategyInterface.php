<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Naming;

use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Interface StrategyInterface
 */
interface StrategyInterface
{
    /**
     * @param TypeNameInterface $name
     * @param iterable|ValueInterface[] $arguments
     * @return string
     */
    public function resolve(TypeNameInterface $name, iterable $arguments): string;
}
