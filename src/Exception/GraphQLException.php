<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Position\Position;
use Phplrt\Position\PositionInterface;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Highlight\Highlight;

/**
 * Class GraphQLException
 */
class GraphQLException extends \LogicException
{
    /**
     * @var string[]
     */
    private const INTERNAL_NAMESPACES = [
        'Railt',
        'Phplrt',
    ];

    /**
     * @var PositionInterface
     */
    private PositionInterface $position;

    /**
     * @param string $message
     * @param Node $ast
     * @param \Throwable|null $prev
     * @return static
     */
    public static function fromAst(string $message, Node $ast = null, \Throwable $prev = null): self
    {
        $instance = new static($message, $prev ? $prev->getCode() : 0, $prev);
        $instance->position = new Position(0, $instance->getLine(), 0);

        if ($ast && $ast->loc) {
            $instance->position = $ast->loc->getStartPosition();

            self::patch($instance, $ast->loc->source,
                fn(Highlight $hl): string => $hl->render($ast->loc->source, $ast->loc->getStartPosition(),
                    $ast->loc->getEndPosition())
            );
        }

        return $instance;
    }

    /**
     * @param GraphQLException $instance
     * @param ReadableInterface $source
     * @param \Closure $message
     * @return void
     */
    private static function patch(self $instance, ReadableInterface $source, \Closure $message): void
    {
        if ($source instanceof FileInterface) {
            $instance->file = $source->getPathname();
            $instance->line = $instance->position->getLine();
        } else {
            [$instance->file, $instance->line] = self::extractFromTrace(
                \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS)
            );
        }

        $instance->message .= \PHP_EOL . $message(Highlight::getInstance());
    }

    /**
     * @param array $trace
     * @return array
     */
    private static function extractFromTrace(array $trace): array
    {
        ['file' => $file, 'line' => $line] = \reset($trace);

        do {
            $item = \array_shift($trace);

            if (isset($item['file'], $item['line'])) {
                ['file' => $file, 'line' => $line] = $item;
            }

            foreach (self::INTERNAL_NAMESPACES as $namespace) {
                if (\strpos($item['class'] ?? '', $namespace) === 0) {
                    continue 2;
                }
            }

            break;
        } while (\count($trace));

        return [$file, $line];
    }

    /**
     * @param string $message
     * @param ReadableInterface $source
     * @param TokenInterface $token
     * @param \Throwable|null $prev
     * @return static
     */
    public static function fromToken(
        string $message,
        ReadableInterface $source,
        TokenInterface $token,
        \Throwable $prev = null
    ): self {
        [$offset, $length] = [$token->getOffset(), $token->getBytes()];

        return static::fromOffset($message, $source, $offset, $length, $prev);
    }

    /**
     * @param string $message
     * @param ReadableInterface $source
     * @param int $offset
     * @param int $length
     * @param \Throwable|null $prev
     * @return static
     */
    public static function fromOffset(
        string $message,
        ReadableInterface $source,
        int $offset,
        int $length = 1,
        \Throwable $prev = null
    ): self {
        $instance = new static($message, $prev ? $prev->getCode() : 0, $prev);
        $instance->position = Position::fromOffset($source, $offset);

        self::patch($instance, $source,
            fn(Highlight $hl): string => $hl->renderByLength($source, $instance->position, $length + 1)
        );

        return $instance;
    }

    /**
     * @return PositionInterface
     */
    public function getPosition(): PositionInterface
    {
        return $this->position;
    }
}
