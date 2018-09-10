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
 * Trait InterfacesProvider
 */
trait InterfacesProvider
{
    /**
     * @return iterable|string[]
     */
    public function getInterfaces(): iterable
    {
        if ($children = $this->first('TypeDefinitionImplementations', 1)) {
            foreach ($children as $child) {
                yield $child->getChild(0)->getValue();
            }
        }
    }
}
