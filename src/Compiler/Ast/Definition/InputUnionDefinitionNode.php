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
 * Class InputUnionDefinitionNode
 */
class InputUnionDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return iterable|TypeNameNode[]
     */
    public function getUnitedTypes(): iterable
    {
        $unites = $this->first('InputUnionDefinitionTargets', 1);

        if ($unites) {
            foreach ($unites as $type) {
                yield $type;
            }
        }
    }
}
