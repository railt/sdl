<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Dependent;

use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\ProvidesType;
use Railt\SDL\Frontend\AST\ProvidesDescription;
use Railt\SDL\Frontend\AST\ProvidesName;
use Railt\SDL\Frontend\AST\Support\DependentNameProvider;
use Railt\SDL\Frontend\AST\Support\DescriptionProvider;

/**
 * Class DependentTypeDefinitionNode
 */
abstract class DependentTypeDefinitionNode extends Rule implements ProvidesName, ProvidesDescription, ProvidesType
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
