<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

use Railt\SDL\IR\SymbolTable\VarSymbolInterface;

/**
 * Interface SymbolTableInterface
 */
interface SymbolTableInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param VarSymbolInterface $record
     * @return int
     */
    public function declare(VarSymbolInterface $record): int;

    /**
     * @param int $addr
     * @return VarSymbolInterface
     */
    public function fetch(int $addr): VarSymbolInterface;
}
