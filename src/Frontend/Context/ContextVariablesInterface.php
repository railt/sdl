<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Context;

use Railt\SDL\IR\SymbolTable\VarSymbolInterface;
use Railt\SDL\IR\Type\TypeInterface;

/**
 * Interface ContextVariablesInterface
 */
interface ContextVariablesInterface extends \IteratorAggregate
{
    /**
     * @param string $var
     * @return VarSymbolInterface
     */
    public function fetch(string $var): VarSymbolInterface;

    /**
     * @param string $var
     * @param TypeInterface|null $type
     * @return VarSymbolInterface
     */
    public function declare(string $var, TypeInterface $type = null): VarSymbolInterface;
}
