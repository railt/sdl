<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR;

use Railt\Io\Readable;

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
    public const RL_DEFINE = 0x02;

    /**
     * @var int
     */
    public const RL_FETCH = 0x03;

    /**
     * @var int
     */
    public const RL_FETCH_DEEP = 0x04;

    /**
     * @var int
     */
    public const RL_NEW = 0x05;

    /**
     * @var int
     */
    public const RL_ASSERT_COMPARE = 0x10;

    /**
     * @var int
     */
    public const RL_ADD_DESCRIPTION = 0x20;

    /**
     * @var int
     */
    public const RL_ADD_DEFINITION = 0x21;

    /**
     * @var int
     */
    public const RL_ADD_FIELD = 0x22;

    /**
     * @var int
     */
    public const RL_ADD_ARGUMENT = 0x22;

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return int
     */
    public function getOffset(): int;

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

    /**
     * @return iterable|mixed[]
     */
    public function getOperands(): iterable;

    /**
     * @return Readable
     */
    public function getFile(): Readable;

    /**
     * @return string
     */
    public function __toString(): string;
}
