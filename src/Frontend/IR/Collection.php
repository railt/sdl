<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR;

use Railt\Io\Readable;
use Railt\SDL\Frontend\Context;

/**
 * Class Collection
 */
class Collection implements \IteratorAggregate
{
    /**
     * @var int
     */
    private static $lastId = 0;

    /**
     * @var array|OpcodeInterface[]
     */
    private $opcodes = [];

    /**
     * @var Context
     */
    private $ctx;

    /**
     * Heap constructor.
     * @param Context $ctx
     */
    public function __construct(Context $ctx)
    {
        $this->ctx = $ctx;
    }

    /**
     * @param OpcodeInterface|Opcode $opcode
     * @param Readable $readable
     * @param int $offset
     * @return Opcode|OpcodeInterface
     */
    public function add(OpcodeInterface $opcode, Readable $readable, int $offset = 0): OpcodeInterface
    {
        $id = self::$lastId++;

        $joinable = $opcode->mount($id, $readable, $offset);

        $this->opcodes[$id] = $joinable;

        $this->ctx->match($joinable);

        return $joinable;
    }

    /**
     * @return \SplDoublyLinkedList|Opcode[]|OpcodeInterface[]
     */
    public function getIterator(): iterable
    {
        yield from $this->opcodes;
    }
}
