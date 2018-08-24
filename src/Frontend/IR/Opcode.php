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
use Railt\Reflection\Contracts\TypeInterface;
use Railt\SDL\Frontend\AST\Value\ValueInterface;

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
    protected $operation;

    /**
     * @var array
     */
    protected $operands;

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
     * @return int
     */
    public function getOperation(): int
    {
        return $this->operation;
    }

    /**
     * @return array
     */
    public function getOperands(): array
    {
        return $this->operands;
    }

    /**
     * @param int $id
     * @param Readable $readable
     * @param int $offset
     * @return JoinableOpcode
     */
    public function join(int $id, Readable $readable, int $offset = 0): JoinableOpcode
    {
        return new JoinableOpcode($this, $id, $readable, $offset);
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function operandToString($value): string
    {
        switch (true) {
            case $value instanceof ValueInterface:
                return $value->toString();

            case $value instanceof TypeInterface:
                return '$' . $value->getName();

            case $value instanceof JoinableOpcode:
                return '!' . $value->getId();

            case $value instanceof OpcodeInterface:
                return $value->getName();

            case $value instanceof Readable:
                return 'file:' . $value->getPathname();

            case \is_bool($value):
                return '(php:bool)' . ($value ? 'true' : 'false');

            case $value === null:
                return '(php:null)null';

            case \is_scalar($value):
                $type = \gettype($value);
                $minified = \preg_replace('/\s+/', ' ', (string)$value);
                return '(php:' . $type . ')"' . \addcslashes($minified, '"') . '"';

            case \is_object($value):
                return \get_class($value) . '#' . \spl_object_hash($value);
        }

        return '';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $operands = \array_map(function ($value): string {
            return $this->operandToString($value);
        }, $this->operands);

        return \sprintf('%-20s %-20s', $this->getName(), '{' . \implode(', ', $operands) . '}');
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
                return 'RL_NOP';
            }
        }

        return static::$opcodes[$operation] ?? static::$opcodes[static::RL_NOP];
    }
}
