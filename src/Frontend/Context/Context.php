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
use Railt\SDL\IR\SymbolTable\VarSymbol;
use Railt\SDL\IR\SymbolTable\VarSymbolInterface;
use Railt\SDL\IR\SymbolTableInterface;
use Railt\SDL\IR\Type\TypeInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class Context
 */
class Context implements ContextInterface
{
    /**
     * @var ContextInterface
     */
    protected $parent;

    /**
     * @var TypeNameInterface
     */
    protected $name;

    /**
     * @var SymbolTableInterface
     */
    protected $table;

    /**
     * @var array|int[]
     */
    protected $names = [];

    /**
     * Context constructor.
     * @param SymbolTableInterface $table
     * @param ContextInterface $parent
     * @param TypeNameInterface $name
     */
    public function __construct(ContextInterface $parent, SymbolTableInterface $table, TypeNameInterface $name)
    {
        $this->name   = $name;
        $this->table  = $table;
        $this->parent = $parent;
    }

    /**
     * @return VarSymbolInterface[]|\Traversable<int,VarSymbolInterface>
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->names as $name => $id) {
            yield $id => $this->table->fetch($id);
        }

        yield from $this->parent;
    }

    /**
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface
    {
        return $this->name;
    }

    /**
     * @param TypeNameInterface $name
     * @return ContextInterface
     */
    public function create(TypeNameInterface $name): ContextInterface
    {
        return new self($this, $this->table, $name);
    }

    /**
     * @return ContextInterface
     */
    public function close(): ContextInterface
    {
        return $this->parent;
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

        return $this->parent->fetch($var);
    }

    /**
     * @param string $var
     * @return bool
     */
    protected function has(string $var): bool
    {
        return isset($this->names[$var]) || \array_key_exists($var, $this->names);
    }

    /**
     * @param string $var
     * @return int
     */
    protected function addr(string $var): int
    {
        return $this->names[$var];
    }

    /**
     * @param string $var
     * @param TypeInterface|null $type
     * @return VarSymbolInterface
     */
    public function declare(string $var, TypeInterface $type = null): VarSymbolInterface
    {
        $record = new VarSymbol($var, $type);

        $this->names[$var] = $this->table->declare($record);

        return $record;
    }

    /**
     * @return ContextInterface
     */
    public function getParent(): ContextInterface
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'name'  => $this->name->getFullyQualifiedName(),
            'file'  => $this->getFile()->getPathname(),
            'scope' => $this->names,
            'table' => $this->table,
        ];
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->parent->getFile();
    }
}
