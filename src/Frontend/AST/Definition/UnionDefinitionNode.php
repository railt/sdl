<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition;

/**
 * Class UnionDefinitionNode
 */
class UnionDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return iterable|string[]
     */
    public function getUnionDefinitions(): iterable
    {
        if ($targets = $this->first('UnionDefinitionTargets', 1)) {
            foreach ($targets as $target) {
                yield $target->getChild(0)->getValue();
            }
        }
    }
}
