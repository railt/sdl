<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

/**
 * @mixin PriorityInterface
 */
trait Priority
{
    /**
     * @var int
     */
    protected $priority = 0;

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
