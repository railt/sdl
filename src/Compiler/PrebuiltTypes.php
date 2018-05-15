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
use Railt\SDL\Compiler\Context\ProvidesTypes;

/**
 * Interface PrebuiltTypes
 */
interface PrebuiltTypes
{
    /**
     * @param Readable $file
     * @return ProvidesTypes
     */
    public function extract(Readable $file): ProvidesTypes;
}
