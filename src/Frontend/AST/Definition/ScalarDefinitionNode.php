<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition;

/**
 * Class ScalarDefinitionNode
 */
class ScalarDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return null|string
     */
    public function getExtends(): ?string
    {
        if ($extends = $this->first('TypeDefinitionExtends', 1)) {
            return $extends->getChild(0)->getChild(0)->getValue();
        }

        return null;
    }
}
