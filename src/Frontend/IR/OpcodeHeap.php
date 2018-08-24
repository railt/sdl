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
 * Class Heap
 */
class OpcodeHeap implements \IteratorAggregate
{
    /**
     * @var \SplDoublyLinkedList
     */
    private $opcodes;

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
        $this->opcodes = new \SplDoublyLinkedList();
        $this->opcodes->setIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO);
        $this->ctx = $ctx;
    }

    /**
     * @param Opcode $opcode
     * @param Readable $readable
     * @param int $offset
     * @return JoinableOpcode
     */
    public function add(Opcode $opcode, Readable $readable, int $offset = 0): JoinableOpcode
    {
        $id = $this->opcodes->count();

        $joinable = $opcode->join($id, $readable, $offset);

        $this->opcodes->push($joinable);

        $this->ctx->match($joinable);

        return $joinable;
    }

    /**
     * @return \SplDoublyLinkedList|JoinableOpcode[]|OpcodeInterface[]
     */
    public function getIterator(): \SplDoublyLinkedList
    {
        return $this->opcodes;
    }
}
