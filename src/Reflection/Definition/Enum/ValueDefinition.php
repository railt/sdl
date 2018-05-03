<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Definition\Enum;

use Railt\SDL\Reflection\Definition\Common\Dependent;
use Railt\SDL\Reflection\Definition\TypeDefinition;

/**
 * Interface ValueDefinition
 */
interface ValueDefinition extends TypeDefinition, Dependent
{
    /**
     * @return string
     */
    public function getValue(): string;
}
