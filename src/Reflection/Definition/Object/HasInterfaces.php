<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Definition\Object;

use Railt\SDL\Reflection\Definition\InterfaceDefinition;

/**
 * Interface HasInterfaces
 */
interface HasInterfaces
{
    /**
     * @return iterable|InterfaceDefinition[]
     */
    public function getInterfaces(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool;

    /**
     * @param string $name
     * @return null|InterfaceDefinition
     */
    public function getInterface(string $name): ?self;

    /**
     * @return int
     */
    public function getNumberOfInterfaces(): int;
}
