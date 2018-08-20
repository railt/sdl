<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Dependent;

use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Ast\ProvidesDefinition;
use Railt\SDL\Ast\ProvidesDescription;
use Railt\SDL\Ast\ProvidesName;
use Railt\SDL\Ast\Support\DependentNameProvider;
use Railt\SDL\Ast\Support\DescriptionProvider;

/**
 * Class DependentTypeDefinitionNode
 */
abstract class DependentTypeDefinitionNode extends Rule implements ProvidesName, ProvidesDescription, ProvidesDefinition
{
    use DependentNameProvider;
    use DescriptionProvider;

    /**
     * @return int
     */
    public function getOffset(): int
    {
        $node = $this->getNameNode();

        if ($node instanceof RuleInterface) {
            return $node->getOffset();
        }

        return parent::getOffset();
    }
}
