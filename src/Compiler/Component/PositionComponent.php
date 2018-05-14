<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

use Railt\Io\Position;
use Railt\Io\Readable;

/**
 * Class PositionComponent
 */
class PositionComponent implements ComponentInterface
{
    /**
     * @var int
     */
    private $offset;

    /**
     * @var Readable
     */
    private $file;

    /**
     * @var Position|null
     */
    private $position;

    /**
     * PositionComponent constructor.
     * @param Readable $file
     * @param int $offset
     */
    public function __construct(Readable $file, int $offset)
    {
        $this->file   = $file;
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->getPosition()->getLine();
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        if ($this->position === null) {
            $this->position = $this->file->getPosition($this->offset);
        }

        return $this->position;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->getPosition()->getColumn();
    }
}


