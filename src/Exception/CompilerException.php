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

/**
 * Class CompilerException
 */
class CompilerException extends ExternalFileException
{
    /**
     * @param string $message
     * @param array $args
     * @return CompilerException
     */
    public function rename(string $message, ...$args): CompilerException
    {
        $this->message = \sprintf($message, ...$args);

        return $this;
    }

    /**
     * @param Definition $def
     * @return CompilerException
     */
    public function in(Definition $def): CompilerException
    {
        $this->throwsIn($def->getFile(), $def->getLine(), $def->getColumn());

        return $this;
    }
}
