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
 * Class JoinableOpcode
 */
class JoinedOpcode extends Opcode implements PositionInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Readable
     */
    private $file;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var \Closure
     */
    private $description;

    /**
     * JoinableOpcode constructor.
     * @param OpcodeInterface|self $opcode
     * @param int $id
     * @param Readable $file
     * @param int $offset
     */
    public function __construct(OpcodeInterface $opcode, int $id, Readable $file, int $offset = 0)
    {
        parent::__construct($opcode->getOperation(), ...$opcode->operands);

        $this->id          = $id;
        $this->file        = $file;
        $this->offset      = $offset;

        $this->description = function () use ($opcode): string {
            return \trim((string)(new \ReflectionObject($opcode))->getDocComment(), " \t\n\r\0\x0B/*");
        };
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return \preg_replace_callback('/\$(\d+)/iu', function (array $m): string {
            return $this->operandToString($this->operands[(int)$m[1]] ?? null);
        }, ($this->description)());
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    /**
     * @return PositionInterface
     */
    private function getPosition(): PositionInterface
    {
        return $this->file->getPosition($this->offset);
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->getPosition()->getLine();
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->getPosition()->getColumn();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return \vsprintf('%4s | %-80s %s:%d', [
            '#' . $this->getId(),
            parent::__toString(),
            $this->getFile()->getPathname(),
            $this->getLine(),
        ]);
    }
}
