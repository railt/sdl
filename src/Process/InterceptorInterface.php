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
 * Interface InterceptorInterface
 */
interface InterceptorInterface
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool;

    /**
     * @param mixed $value
     * @return mixed
     */
    public function invoke($value);
}
