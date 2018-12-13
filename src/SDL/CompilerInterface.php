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
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Reflection;

/**
 * Interface CompilerInterface
 */
interface CompilerInterface
{
    /**
     * @var string
     */
    public const VERSION = '1.3.0';

    /**
     * @param Readable $schema
     * @return Document
     */
    public function compile(Readable $schema): Document;

    /**
     * @param Readable $schema
     * @return Reflection
     */
    public function preload(Readable $schema): Reflection;
}
