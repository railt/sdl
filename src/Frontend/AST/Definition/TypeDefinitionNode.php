<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition;

use Railt\Parser\Ast\Rule;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\ProvidesDescription;
use Railt\SDL\Frontend\AST\ProvidesDirectiveNodes;
use Railt\SDL\Frontend\AST\ProvidesName;
use Railt\SDL\Frontend\AST\ProvidesOpcode;
use Railt\SDL\Frontend\AST\ProvidesType;
use Railt\SDL\Frontend\AST\Support\DescriptionProvider;
use Railt\SDL\Frontend\AST\Support\DirectivesProvider;
use Railt\SDL\Frontend\AST\Support\TypeNameProvider;
use Railt\SDL\Frontend\Context;
use Railt\SDL\Frontend\IR\Opcode;
use Railt\SDL\Frontend\IR\Opcode\DefineOpcode;

/**
 * Class TypeDefinitionNode
 */
abstract class TypeDefinitionNode extends Rule implements ProvidesName, ProvidesType, ProvidesDescription, ProvidesDirectiveNodes, ProvidesOpcode
{
    use TypeNameProvider;
    use DirectivesProvider;
    use DescriptionProvider;

    /**
     * @param Context $context
     * @return iterable
     */
    public function getOpcodes(Context $context): iterable
    {
        $current = $context->create();

        $joinable = yield new DefineOpcode($this->getFullName(), $this->getType(), $current);

        if ($description = $this->getDescriptionNode()) {
            yield $description => new Opcode(Opcode::RL_DESCRIPTION, $joinable, $this->getDescription());
        }
    }

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
