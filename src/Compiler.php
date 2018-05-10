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
     * Compiler constructor.
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function __construct()
    {
        $this->headers = new HeadingsTable();
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
