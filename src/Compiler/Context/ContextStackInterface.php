<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Context;

use Railt\Io\Readable;

/**
 * Interface ContextStackInterface
 */
interface ContextStackInterface
{
    /**
     * @param Readable|null $file
     * @return LocalContextInterface
     */
    public function create(Readable $file = null): LocalContextInterface;

    /**
     * @param Readable $file
     * @param \Closure $then
     * @return LocalContextInterface
     */
    public function transact(Readable $file, \Closure $then): LocalContextInterface;

    /**
     * @return LocalContextInterface
     */
    public function current(): LocalContextInterface;

    /**
     * @return LocalContextInterface
     */
    public function complete(): LocalContextInterface;
}
