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
use Railt\SDL\Frontend\IR\Opcode\AddDefinitionOpcode;
use Railt\SDL\Frontend\IR\Opcode\AddDescriptionOpcode;
use Railt\SDL\Frontend\IR\Opcode\DefineOpcode;
use Railt\SDL\Frontend\IR\Value\NullValue;
use Railt\SDL\Frontend\IR\Value\TypeValue;

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
        $parent = $context->create();
        $current = yield new DefineOpcode($this->getFullNameValue(), new TypeValue($this->getType()));

        yield function () use ($current, $parent) {
            yield new AddDefinitionOpcode($current, $parent);
        };

        if (! ($description = $this->getDescriptionValue()) instanceof NullValue) {
            yield new AddDescriptionOpcode($current, $description);
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
