<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Context;

use Railt\Io\Readable;
use Railt\SDL\Exception\NotFoundException;
use Railt\SDL\IR\SymbolTable\VarSymbolInterface;
use Railt\SDL\IR\SymbolTableInterface;
use Railt\SDL\IR\Type\Name;

/**
 * Class GlobalContext
 */
class GlobalContext extends Context implements GlobalContextInterface
{
    /**
     * @var Readable
     */
    private $file;

    /**
     * GlobalContext constructor.
     * @param Readable $file
     * @param SymbolTableInterface $table
     */
    public function __construct(Readable $file, SymbolTableInterface $table)
    {
        $this->file = $file;

        parent::__construct($this, $table, Name::empty(true));
    }

    /**
     * @return VarSymbolInterface[]|\Traversable<int,VarSymbolInterface>
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->names as $name => $id) {
            yield $id => $this->table->fetch($id);
        }
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    /**
     * @param string $var
     * @return VarSymbolInterface
     */
    public function fetch(string $var): VarSymbolInterface
    {
        if ($this->has($var)) {
            return $this->table->fetch($this->addr($var));
        }

        $error = \sprintf('Undefined variable $%s', $var);
        throw new NotFoundException($error);
    }
}
