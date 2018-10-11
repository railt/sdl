<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Process;

/**
 * Interface DeferredInterface
 */
interface DeferredInterface
{
    /**
     * @param callable $then
     * @return DeferredInterface
     */
    public function then(callable $then): self;

    /**
     * @param mixed $value
     * @return mixed
     */
    public function invoke($value);
}
