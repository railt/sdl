<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Stack;

use Railt\Io\Position;
use Railt\Io\Readable;

/**
 * Class Item
 */
class Item
{
    /**
     * @var Position
     */
    private $position;

    /**
     * @var Readable
     */
    private $file;

    /**
     * @var string|callable
     */
    private $value;

    /**
     * Item constructor.
     * @param Readable $file
     * @param Position $position
     * @param string|callable $value
     */
    public function __construct(Readable $file, Position $position, $value)
    {
        $this->file     = $file;
        $this->value    = $value;
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        if (\is_callable($this->value)) {
            $this->value = (string)($this->value)();
        }

        return $this->value;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }
}
