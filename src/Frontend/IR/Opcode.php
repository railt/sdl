<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR;

use Railt\Io\PositionInterface;
use Railt\Io\Readable;

/**
 * Class Opcode
 */
class Opcode implements OpcodeInterface
{
    /**
     * @var int
     */
    private $offset;

    /**
     * @var int|null
     */
    private $line;

    /**
     * @var int|null
     */
    private $column;

    /**
     * @var Readable
     */
    private $readable;

    /**
     * @var int
     */
    private $operation;

    /**
     * @var iterable
     */
    private $operands;

    /**
     * Opcode constructor.
     * @param Readable $readable
     * @param int $offset
     * @param int $operation
     * @param iterable $operands
     */
    public function __construct(Readable $readable, int $offset, int $operation, iterable $operands)
    {
        $this->readable  = $readable;
        $this->operation = $operation;
        $this->operands  = $operands;
        $this->offset    = $offset;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->readable;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        if ($this->line === null) {
            $this->line = $this->getPosition()->getLine();
        }

        return $this->line;
    }

    /**
     * @return PositionInterface
     */
    private function getPosition(): PositionInterface
    {
        return $this->readable->getPosition($this->offset);
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        if ($this->column === null) {
            $this->column = $this->getPosition()->getLine();
        }

        return $this->column;
    }

    /**
     * @return int
     */
    public function getOperation(): int
    {
        return $this->operation;
    }

    /**
     * @return iterable
     */
    public function getOperands(): iterable
    {
        foreach ($this->operands as $operand) {
            yield $operand;
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->operation . ' ' . \implode(', ', $this->operands);
    }
}
