<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\Reflection\Contracts\Definition;

/**
 * Interface BuilderInterface
 */
interface BuilderInterface
{
    /**
     * @return Definition
     */
    public function build(): Definition;
}
