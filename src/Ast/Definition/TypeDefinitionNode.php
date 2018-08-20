<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Ast\ProvidesDefinition;
use Railt\SDL\Ast\ProvidesDescription;
use Railt\SDL\Ast\ProvidesDirectiveNodes;
use Railt\SDL\Ast\ProvidesName;
use Railt\SDL\Ast\Support\DescriptionProvider;
use Railt\SDL\Ast\Support\DirectivesProvider;
use Railt\SDL\Ast\Support\TypeNameProvider;

/**
 * Class TypeDefinitionNode
 */
abstract class TypeDefinitionNode extends Rule implements
    ProvidesName,
    ProvidesDefinition,
    ProvidesDescription,
    ProvidesDirectiveNodes
{
    use TypeNameProvider;
    use DirectivesProvider;
    use DescriptionProvider;

    /**
     * @return int
     */
    public function getOffset(): int
    {
        $node = $this->getTypeNameNode();

        if ($node instanceof RuleInterface) {
            return $node->getOffset();
        }

        return parent::getOffset();
    }
}
