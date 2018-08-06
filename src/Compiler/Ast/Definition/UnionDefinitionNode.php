<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\SDL\Compiler\Ast\TypeNameNode;

/**
 * Class UnionDefinitionNode
 */
class UnionDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return iterable|TypeNameNode[]
     */
    public function getUnitedTypes(): iterable
    {
        $unites = $this->first('UnionDefinitionTargets', 1);

        if ($unites) {
            foreach ($unites as $type) {
                yield $type;
            }
        }
    }
}
