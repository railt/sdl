<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Naming;

use Railt\Reflection\Invocation\ArgumentInterface;
use Railt\SDL\IR\TypeNameInterface;

/**
 * Interface StrategyInterface
 */
interface StrategyInterface
{
    /**
     * @param TypeNameInterface $name
     * @param iterable|ArgumentInterface[] $arguments
     * @return string
     */
    public function reduce(TypeNameInterface $name, iterable $arguments): string;
}
