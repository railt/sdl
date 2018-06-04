<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Heap;

/**
 * Interface PriorityInterface
 */
interface PriorityInterface
{
    public const DEFAULT     = 0x01;
    public const INVOCATION  = 0x02;
    public const EXTENSION   = 0x03;
    public const DEFINITION  = 0x04;
    public const INSTRUCTION = 0x05;

    /**
     * @return int
     */
    public function getPriority(): int;
}
