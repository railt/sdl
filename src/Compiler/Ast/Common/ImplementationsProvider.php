<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Common;

use Railt\SDL\Compiler\Ast\TypeNameNode;

/**
 * Trait ImplementationsProvider
 */
trait ImplementationsProvider
{
    /**
     * @return iterable|TypeNameNode
     */
    public function getImplementations(): iterable
    {
        $implements = $this->first('Implements', 1);

        if ($implements) {
            foreach ($implements as $interface) {
                yield $interface;
            }
        }
    }
}
