<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Context;

use Railt\Io\Readable;
use Railt\SDL\Frontend\Type\TypeNameInterface;

/**
 * Interface ContextInterface
 */
interface ContextInterface
{
    /**
     * @return Readable
     */
    public function getFile(): Readable;

    /**
     * @return TypeNameInterface
     */
    public function current(): TypeNameInterface;

    /**
     * @param TypeNameInterface $name
     * @return TypeNameInterface
     */
    public function create(TypeNameInterface $name): TypeNameInterface;

    /**
     * @return TypeNameInterface
     */
    public function close(): TypeNameInterface;
}
