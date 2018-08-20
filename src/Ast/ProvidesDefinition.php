<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast;

use Railt\Reflection\Contracts\Definition;

/**
 * Interface ProvidesDefinition
 */
interface ProvidesDefinition
{
    /**
     * @param Definition $context
     * @return Definition
     */
    public function resolve(Definition $context): Definition;
}
