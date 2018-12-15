<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\TypeLoader;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definition;

/**
 * Interface TypeLoaderInterface
 */
interface TypeLoaderInterface
{
    /**
     * @param string $type
     * @param Definition|null $from
     * @return Readable|null
     */
    public function load(string $type, Definition $from = null): ?Readable;
}
