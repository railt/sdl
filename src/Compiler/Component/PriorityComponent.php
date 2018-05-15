<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Component;

/**
 * Class PriorityComponent
 */
class PriorityComponent implements ComponentInterface
{
    public const DEFAULT    = 0x00;
    public const INVOCATION = 0x01;
    public const EXTENSION  = 0x02;
    public const DEFINITION = 0x03;

    /**
     * @var int
     */
    private $priority;

    /**
     * PriorityComponent constructor.
     * @param int $priority
     */
    public function __construct(int $priority = self::DEFAULT)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
