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
use Railt\SDL\Heap\HeapInterface;

/**
 * Interface PipelineInterface
 */
interface PipelineInterface
{
    /**
     * @param Readable $file
     * @return HeapInterface
     */
    public function parse(Readable $file): HeapInterface;
}
