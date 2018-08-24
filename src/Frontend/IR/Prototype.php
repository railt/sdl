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

/**
 * Class Prototype
 */
class Prototype
{
    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $operation;

    /**
     * @var iterable
     */
    private $operands;

    /**
     * Prototype constructor.
     * @param int $offset
     * @param int $operation
     * @param iterable $operands
     */
    public function __construct(int $offset, int $operation, iterable $operands)
    {
        $this->offset    = $offset;
        $this->operation = $operation;
        $this->operands  = $operands;
    }

    /**
     * @param Readable $file
     * @param int $id
     * @return OpcodeInterface
     */
    public function create(Readable $file, int $id): OpcodeInterface
    {
        return new Opcode($file, $this->offset, $this->operation, $this->operands);
    }
}
