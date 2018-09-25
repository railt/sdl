<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Scalar;

use Railt\SDL\Frontend\Type\TypeInterface;

/**
 * Interface ScalarInterface
 */
interface ScalarInterface
{
    /**
     * @return mixed
     */
    public function toPrimitive();

    /**
     * @return TypeInterface
     */
    //public function getType(): TypeInterface;
}
