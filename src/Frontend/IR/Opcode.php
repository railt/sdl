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
use Railt\SDL\Renderer;

/**
 * Class Opcode
 */
class Opcode implements OpcodeInterface
{
    /**
     * @var array|string[]|null
     */
    protected static $opcodes;

    /**
     * @var int
     */
    private $operation;

    /**
     * @var array
     */
    private $operands;

    /**
     * @var int
     */
    private $id;

    /**
     * @var Readable
     */
    private $file;

    /**
     * @var int|null
     */
    private $offset;

    /**
     * Opcode constructor.
     * @param int $operation
     * @param mixed ...$operands
     */
    public function __construct(int $operation, ...$operands)
    {
        $this->operation = $operation;
        $this->operands = $operands;
    }

    /**
     * @param int $operation
     * @return Opcode
     */
    public function rebind(int $operation): Opcode
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * @param int $id
     * @param Readable $file
     * @param int $offset
     * @return Opcode
     */
    public function mount(int $id, Readable $file, int $offset = 0): Opcode
    {
        $this->id = $id;
        $this->file = $file;
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        \assert($this->id !== null, 'Opcode is unmounted');

        return $this->id;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        \assert($this->offset !== null, 'Opcode is unmounted');

        return $this->offset;
    }

    /**
     * @return int
     */
    public function getOperation(): int
    {
        return $this->operation;
    }

    /**
     * @param int $id
     * @return mixed|null
     */
    public function getOperand(int $id)
    {
        return $this->operands[$id] ?? null;
    }

    /**
     * @return iterable
     */
    public function getOperands(): iterable
    {
        return $this->operands;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        \assert($this->file !== null, 'Opcode is unmounted');

        return $this->file;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $operands = [];

        foreach ($this->operands as $i => $operand) {
            $operands[] = \sprintf('%d => %s', $i, Renderer::render($operand));
        }

        return \vsprintf('#%s %s {%s}', [
            $this->id ?? '?',
            $this->getName(),
            \implode(', ', $operands),
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::getOperationName($this->operation);
    }

    /**
     * @param int $operation
     * @return string
     */
    public static function getOperationName(int $operation): string
    {
        if (static::$opcodes === null) {
            static::$opcodes = [];

            try {
                $reflection = new \ReflectionClass(static::class);
                foreach ($reflection->getConstants() as $name => $value) {
                    static::$opcodes[$value] = $name;
                }
            } catch (\ReflectionException $e) {
                return '';
            }
        }

        return static::$opcodes[$operation] ?? static::$opcodes[static::RL_NOP];
    }
}
