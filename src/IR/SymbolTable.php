<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\NotFoundException;
use Railt\SDL\IR\SymbolTable\VarSymbol;
use Railt\SDL\IR\SymbolTable\VarSymbolInterface;

/**
 * Class SymbolTable
 */
class SymbolTable implements SymbolTableInterface
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var array|VarSymbolInterface[]
     */
    private $variables = [];

    /**
     * @param VarSymbolInterface $record
     * @return int
     */
    public function declare(VarSymbolInterface $record): int
    {
        $id = $this->id++;

        $this->variables[$id] = $record;

        return $id;
    }

    /**
     * @param int $addr
     * @return VarSymbolInterface
     * @throws NotFoundException
     */
    public function fetch(int $addr): VarSymbolInterface
    {
        if (! isset($this->variables[$addr])) {
            $error = \sprintf('Mismatched variable address 0x%08x', $addr);
            throw new NotFoundException($error);
        }

        return $this->variables[$addr];
    }

    /**
     * @return VarSymbol[]|\Traversable<int,VarSymbol>
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->variables as $id => $symbol) {
            yield $id => $symbol;
        }
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        $variables = [];
        foreach ($this->variables as $id => $var) {
            $variables[$id] = (string)$var;
        }

        return [
            'size'      => $this->count(),
            'variables' => $variables
        ];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->variables);
    }
}
