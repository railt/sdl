<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Exception;

use Railt\Io\Exception\ExternalExceptionInterface;
use Railt\Io\Exception\ExternalFileException;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definition;

/**
 * Class CompilerException
 */
class CompilerException extends ExternalFileException
{
    /**
     * @var bool
     */
    private $defined = false;

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
     * @param Readable $file
     * @param int $offsetOrLine
     * @param int|null $column
     * @return ExternalExceptionInterface|self
     */
    public function throwsIn(Readable $file, int $offsetOrLine = 0, int $column = null): ExternalExceptionInterface
    {
        if (! $this->defined) {
            $this->defined = true;

            return parent::throwsIn($file, $offsetOrLine, $column);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isPositionDefined(): bool
    {
        return $this->defined;
    }
}
