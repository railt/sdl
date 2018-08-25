<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR;

/**
 * Interface OpcodeInterface
 */
interface OpcodeInterface
{
    /**
     * @var int
     */
    public const RL_NOP = 0x00;

    /**
     * @var int
     */
    public const RL_OPEN = 0x01;

    /**
     * @var int
     */
    public const RL_DESCRIPTION = 0x02;

    /**
     * @var int
     */
    public const RL_DEFINE = 0x03;

    /**
     * @var int
     */
    public const RL_FETCH = 0x04;

    /**
     * @var int
     */
    public const RL_FETCH_DEEP = 0x05;

    /**
     * @var int
     */
    public const RL_ATTACH = 0x06;

    /**
     * @var int
     */
    public const RL_ASSERT_COMPARE = 0x07;

    /**
     * @var int
     */
    public const RL_NEW = 0x08;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getOperation(): int;

    /**
     * @param int $id
     * @return mixed
     */
    public function getOperand(int $id);
}
