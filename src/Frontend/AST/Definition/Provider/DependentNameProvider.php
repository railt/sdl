<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition\Provider;

/**
 * Trait DependentNameProvider
 */
trait DependentNameProvider
{
    /**
     * @return null|string
     */
    public function getFullName(): ?string
    {
        if ($name = $this->first('DependentName', 1)) {
            return $name->getChild(0)->getValue();
        }

        return null;
    }
}
