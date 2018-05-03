<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Definition;

use Railt\SDL\Reflection\Definition\Enum\ValueDefinition;

/**
 * Interface EnumDefinition
 */
interface EnumDefinition extends TypeDefinition
{
    /**
     * @return iterable|ValueDefinition[]
     */
    public function getValues(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name): bool;

    /**
     * @param string $name
     * @return ValueDefinition
     */
    public function getValue(string $name): ?ValueDefinition;
}
