<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Invocation;

use Railt\SDL\Frontend\Type\TypeNameInterface;

/**
 * Interface TypeInvocation
 */
interface TypeInvocation
{
    /**
     * @return TypeNameInterface
     */
    public function getTypeName(): TypeNameInterface;

    /**
     * @return iterable|ArgumentInterface[]
     */
    public function getArguments(): iterable;
}
