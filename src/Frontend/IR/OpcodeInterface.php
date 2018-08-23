<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\IR;

use Railt\Io\PositionInterface;
use Railt\Io\Readable;

/**
 * Interface OpcodeInterface
 */
interface OpcodeInterface extends PositionInterface
{
    /**
     * @var int
     */
    public const C_TYPE_DEFINITION = 0x01;

    /**
     * @return Readable
     */
    public function getFile(): Readable;

    /**
     * {@inheritdoc}
     */
    public function getLine(): int;

    /**
     * {@inheritdoc}
     */
    public function getColumn(): int;

    /**
     * @return int
     */
    public function getOperation(): int;

    /**
     * @return iterable
     */
    public function getOperands(): iterable;
}
