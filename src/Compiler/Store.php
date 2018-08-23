<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document;

/**
 * Class Store
 */
class Store
{
    /**
     * @var array|Document[]
     */
    private $documents = [];

    /**
     * @param Readable $file
     * @param \Closure $otherwise
     * @return Document
     */
    public function memoize(Readable $file, \Closure $otherwise): Document
    {
        if ($this->has($file)) {
            return $this->documents[$file->getHash()];
        }

        return $this->documents[$file->getHash()] = $otherwise($file);
    }

    /**
     * @param Readable $file
     * @return bool
     */
    public function has(Readable $file): bool
    {
        return isset($this->documents[$file->getHash()]);
    }
}
