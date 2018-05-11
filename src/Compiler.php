<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Io\Readable;
use Railt\SDL\Linker\HeadingsTable;
use Railt\SDL\Stack\CallStack;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Compiler
 */
class Compiler
{
    /**
     * @var HeadingsTable
     */
    private $headers;

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * Compiler constructor.
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __construct()
    {
        $this->stack = new CallStack();
        $this->headers = new HeadingsTable($this->stack);
    }

    /**
     * @return CallStackInterface
     */
    public function getStack(): CallStackInterface
    {
        return $this->stack;
    }

    /**
     * @param Readable $file
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    public function parse(Readable $file): void
    {
        $this->headers->extract($file);
    }
}
