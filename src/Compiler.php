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
use Railt\SDL\Compiler\Linker;
use Railt\SDL\Compiler\TypeLoader;
use Railt\SDL\Stack\CallStack;

/**
 * Class Compiler
 */
class Compiler
{
    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var TypeLoader
     */
    private $loader;

    /**
     * Compiler constructor.
     */
    public function __construct()
    {
        $this->stack = new CallStack();
        $this->loader = new TypeLoader($this->stack);
    }

    /**
     * @param callable $then
     * @return Compiler
     */
    public function addLoader(callable $then): Compiler
    {
        $this->loader->addLoader($then);

        return $this;
    }

    /**
     * @param Readable $file
     * @return mixed
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \RuntimeException
     */
    public function compile(Readable $file)/*: Document*/
    {
        $ctx = new Linker($this->loader, $this->stack);

        $ctx->process($file);
    }
}
