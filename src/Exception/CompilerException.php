<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Exception;

use Railt\SDL\Stack\CallStack;

/**
 * Class CompilerException
 */
class CompilerException extends \RuntimeException
{
    /**
     * @var CallStack
     */
    private $stack;

    /**
     * CompilerException constructor.
     * @param string $message
     * @param CallStack $stack
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, CallStack $stack, \Throwable $previous = null)
    {
        $this->stack = $stack;
        parent::__construct($message, 0, $previous);
    }
}
