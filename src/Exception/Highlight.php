<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Position\PositionInterface;

/**
 * Class Highlight
 */
class Highlight
{
    /**
     * @param ReadableInterface $file
     * @param PositionInterface $position
     * @return string
     */
    public function read(ReadableInterface $file, PositionInterface $position): string
    {
        $current = $this->getSourceLine($file, $position);

        return \PHP_EOL .
            $this->renderLine((string)$position->getLine(), $current) .
            $this->renderErrorPosition($position->getColumn())
        ;
    }

    /**
     * @param int $offset
     * @return string
     */
    private function renderErrorPosition(int $offset): string
    {
        return $this->line('') . \str_repeat(' ', \max($offset - 1, 0)) . '^' . \PHP_EOL;
    }

    /**
     * @param string $line
     * @param string $code
     * @return string
     */
    private function renderLine(string $line, string $code): string
    {
        return $this->line($line) . \rtrim($code) . \PHP_EOL;
    }

    /**
     * @param string $line
     * @return string
     */
    private function line(string $line): string
    {
        $line = \str_pad($line, 4, ' ', \STR_PAD_LEFT);

        return ' ' . $line . ' | ';
    }

    /**
     * @param ReadableInterface $file
     * @param PositionInterface $position
     * @return array|string[]
     */
    private function getSourceLine(ReadableInterface $file, PositionInterface $position): string
    {
        [$stream, $i, $line] = [$file->getStream(), 0, ''];

        while (++$i <= $position->getLine() && ! \feof($stream)) {
            $line = \fgets($stream);
        }

        return $line;
    }
}
