<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Invocation;

use Railt\SDL\IR\SymbolTable\PrimitiveInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Interface InvocationInterface
 */
interface InvocationInterface extends PrimitiveInterface
{
    /**
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface;
}
