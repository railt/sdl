<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Exception;

use Railt\Io\Exception\ExternalFileException;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\SDL\Compiler\CallStack;

/**
 * Class CompilerException
 */
class CompilerException extends ExternalFileException
{
    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var bool
     */
    private $skipInnerTrace = true;

    /**
     * @param string $message
     * @param array $args
     * @return CompilerException
     */
    public function rename(string $message, ...$args): self
    {
        $this->message = \sprintf($message, ...$args);

        return $this;
    }

    /**
     * @param Definition $def
     * @return CompilerException
     */
    public function in(Definition $def): self
    {
        $this->throwsIn($def->getFile(), $def->getLine(), $def->getColumn());

        return $this;
    }

    /**
     * @param bool $skip
     * @return CompilerException
     */
    public function skipInnerTrace(bool $skip = true): self
    {
        $this->skipInnerTrace = $skip;

        return $this;
    }

    /**
     * @param CallStack $stack
     * @return CompilerException
     */
    public function using(CallStack $stack): self
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $result  = $this->getHeader() . \PHP_EOL;
        $result .= 'Stack trace:' . \PHP_EOL;
        $result .= $this->getFullTraceAsString();

        return $result;
    }

    /**
     * @return string
     */
    public function getFullTraceAsString(): string
    {
        [$result, $i] = ['', 0];

        if ($this->stack) {
            foreach ($this->stack as $def) {
                $result .= \sprintf('#%d %s(%d): %s', $i++, $def->getFile(), $def->getLine(), $def) . \PHP_EOL;
            }
        }

        foreach (\explode("\n", $this->getTraceAsString()) as $line) {
            if ($this->skipInnerTrace && $this->shouldSkip($line)) {
                continue;
            }

            $result .= \preg_replace('/#\d+\h/iu', \sprintf('#%d ', $i++), $line) . "\n";
        }

        return $result;
    }

    /**
     * @param string $line
     * @return bool
     */
    private function shouldSkip(string $line): bool
    {
        $match = \preg_match('/:\h+Railt\\\\(Parser|SDL|Lexer)/ium', $line);

        return $match === 1;
    }

    /**
     * @return string
     */
    private function getHeader(): string
    {
        return \vsprintf('%s: %s in %s:%d', [
            \get_class($this),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine(),
        ]);
    }

    /**
     * @return array
     */
    public function getSDLTrace(): array
    {
        $result = [];

        if ($this->stack) {
            foreach ($this->stack as $definition) {
                $result[] = \array_filter([
                    'file'   => $definition->getFile(),
                    'line'   => $definition->getLine(),
                    'column' => $definition->getColumn(),
                    'type'   => $definition::getType()->getName(),
                    'name'   => $definition instanceof TypeDefinition ? $definition->getName() : null,
                ]);
            }
        }

        return $result;
    }
}
