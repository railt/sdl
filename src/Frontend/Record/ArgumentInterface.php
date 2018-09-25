<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Record;

use Railt\SDL\Frontend\Type\TypeInterface;

/**
 * Interface ArgumentInterface
 */
interface ArgumentInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;
}
