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
use Railt\SDL\Reflection\Document;

/**
 * Class Compiler
 */
class Compiler
{
    /**
     * Compiler constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param Readable $file
     * @return mixed
     */
    public function parse(Readable $file): Document
    {
    }
}
