<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Ast\TypeNameNode;

/**
 * Class ScalarDefinitionNode
 */
class ScalarDefinitionNode extends TypeDefinitionNode
{
    /**
     * @return null|TypeNameNode
     */
    public function getExtends(): ?TypeNameNode
    {
        /** @var RuleInterface $extends */
        $extends = $this->first('Extends', 1);

        return $extends ? $extends->getChild(0) : null;
    }
}
