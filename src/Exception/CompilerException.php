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
use Railt\SDL\CallStack;

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
     * @param Definition $def
     * @return CompilerException
     */
    public function in(Definition $def): self
    {
        $this->throwsIn($def->getFile(), $def->getLine(), $def->getColumn());

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

        foreach ($this->stack as $def) {
            $result .= \sprintf('#%d %s(%d): %s', $i++, $def->getFile(), $def->getLine(), $def) . \PHP_EOL;
        }

        $result .= \preg_replace_callback('/^#\d+\h/ium', function () use (&$i): string {
            return \sprintf('#%d ', $i++);
        }, $this->getTraceAsString());

        return $result;
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
