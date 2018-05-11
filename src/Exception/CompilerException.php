<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Exception;

use Railt\SDL\Stack\CallStackInterface;
use Railt\SDL\Stack\Item;

/**
 * Class CompilerException
 */
class CompilerException extends \RuntimeException
{
    /**
     * @var CallStackInterface
     */
    protected $stack;

    /**
     * @var int
     */
    private $column = 0;

    /**
     * CompilerException constructor.
     * @param string $message
     * @param CallStackInterface $stack
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', CallStackInterface $stack, \Throwable $previous = null)
    {
        $this->stack = $stack;
        parent::__construct($message, 0, $previous);

        if ($this->stack->count()) {
            $this->extract($this->stack->pop());
        }
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @param Item $item
     */
    private function extract(Item $item): void
    {
        $position = $item->getPosition();

        $this->file   = $item->getFile()->getPathname();
        $this->line   = $position->getLine();
        $this->column = $position->getColumn();
    }

    /**
     * @return iterable|string[]
     */
    private function getStackAsString(): iterable
    {
        $stack = \explode("\n", $this->getTraceAsString());

        /** @var Item $item */
        foreach ($this->stack as $item) {
            $position = $item->getPosition();

            yield \vsprintf('%s(%d): %s', [
                $item->getFile()->getPathname(),
                $position->getLine(),
                $item->getValue(),
            ]);
        }

        yield from \array_map(function (string $line): string {
            return \preg_replace('/#\d+\h+/iu', '', $line);
        }, $stack);
    }

    /**
     * @return string
     */
    private function getHeaderMessage(): string
    {
        return \vsprintf('%s: %s in %s:%d', [
            \get_class($this),
            $this->message,
            $this->file,
            $this->line,
        ]);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $output   = [$this->getHeaderMessage()];
        $output[] = 'Stack trace:';

        foreach ($this->getStackAsString() as $i => $line) {
            $output[] = \sprintf('#%d %s', $i, $line);
        }

        return \implode("\n", $output);
    }
}
