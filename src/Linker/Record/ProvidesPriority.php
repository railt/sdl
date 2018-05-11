<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Record;

/**
 * Interface ProvidesPriority
 */
interface ProvidesPriority
{
    public const PRIORITY_INVOCATION = 0x01;
    public const PRIORITY_EXTENSION  = 0x02;
    public const PRIORITY_DEFINITION = 0x03;

    /**
     * @return int
     */
    public function getPriority(): int;
}
