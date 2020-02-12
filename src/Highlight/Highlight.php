<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Highlight;

use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Position\Position;
use Phplrt\Position\PositionInterface;

/**
 * Class Highlight
 */
final class Highlight implements HighlightInterface
{
    /**
     * @var string
     */
    public string $lineTemplate = ' %s | %s';

    /**
     * @var int
     */
    public int $lineSize = 5;

    /**
     * @var string
     */
    public string $lineDelimiter = \PHP_EOL;

    /**
     * @var string
     */
    public string $errorHighlight = '^';

    /**
     * @var string
     */
    public string $eoi = '<EOI>';

    /**
     * @var self|HighlightInterface|null
     */
    private static ?self $instance = null;

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        return self::$instance ?? self::$instance = new self();
    }

    /**
     * @param ReadableInterface $file
     * @param PositionInterface $from
     * @param PositionInterface $to
     * @return string
     */
    public function render(ReadableInterface $file, PositionInterface $from, PositionInterface $to = null): string
    {
        $source = $this->getCodeLine($file, $from);

        return $this->lines([
            $this->line($source, $from->getLine()),
            $this->error($from->getColumn(), $this->length($source, $from, $to ?? $from)),
        ]);
    }

    /**
     * @param ReadableInterface $file
     * @param PositionInterface $from
     * @param int $length
     * @return string
     */
    public function renderByLength(ReadableInterface $file, PositionInterface $from, int $length = 1): string
    {
        $to = Position::fromPosition($file, $from->getLine(), $from->getColumn() + $length);

        return $this->render($file, $from, $to);
    }

    /**
     * @param string $source
     * @param PositionInterface $from
     * @param PositionInterface $to
     * @return int
     */
    private function length(string $source, PositionInterface $from, PositionInterface $to): int
    {
        $max = \mb_strlen($source) - $from->getColumn();

        if ($from->getLine() === $to->getLine()) {
            return \min($max, $to->getColumn() - $from->getColumn() - 1);
        }

        return $max;
    }

    /**
     * @param iterable|string[] $lines
     * @return string
     */
    private function lines(iterable $lines): string
    {
        $lines = $lines instanceof \Traversable ? \iterator_to_array($lines) : $lines;

        return \implode($this->lineDelimiter, $lines);
    }

    /**
     * @param string $text
     * @param int|null $line
     * @return string
     */
    private function line(string $text, ?int $line = null): string
    {
        $prefix = $line === null
            ? \str_repeat(' ', $this->lineSize)
            : \str_pad($line . '.', $this->lineSize, ' ', \STR_PAD_LEFT);

        return \sprintf($this->lineTemplate, $prefix, \rtrim($text));
    }

    /**
     * @param ReadableInterface $file
     * @param PositionInterface $position
     * @return string
     */
    public function getCodeLine(ReadableInterface $file, PositionInterface $position): string
    {
        [$stream, $i, $line] = [$file->getStream(), 0, ''];

        while (++$i <= $position->getLine() && ! \feof($stream)) {
            $line = \is_string($current = \fgets($stream)) ? $current : $this->eoi;
        }

        return $line;
    }

    /**
     * @param int $offset
     * @param int $length
     * @return string
     */
    private function error(int $offset, int $length): string
    {
        $prefix = \str_repeat(' ', \max($offset - 1, 0));
        $highlight = \str_repeat($this->errorHighlight, \max($length, 1));

        return $this->line($prefix . $highlight, null);
    }
}
