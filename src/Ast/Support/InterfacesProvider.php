<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Support;

use Railt\SDL\Ast\Common\TypeNameNode;
use Railt\SDL\Ast\ProvidesInterfaceNodes;

/**
 * Trait InterfacesProvider
 */
trait InterfacesProvider
{
    /**
     * @return iterable|TypeNameNode[]
     */
    public function getInterfaceNodes(): iterable
    {
        $interfaces = $this->first('TypeDefinitionImplementations', 1);

        if ($interfaces) {
            yield from $interfaces;
        }
    }
}
