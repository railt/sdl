<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Interceptor;

use Railt\Io\Readable;

/**
 * Interface InterceptorInterface
 */
interface InterceptorInterface
{
    /**
     * @param mixed $result
     * @return bool
     */
    public function match($result): bool;

    /**
     * @param Readable $file
     * @param mixed $result
     * @return mixed
     */
    public function apply(Readable $file, $result);
}
