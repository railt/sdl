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
use Railt\SDL\Frontend\AST\ProvidesDescription;
use Railt\SDL\Frontend\AST\ProvidesName;
use Railt\SDL\Frontend\AST\ProvidesOpcode;
use Railt\SDL\Frontend\AST\ProvidesType;
use Railt\SDL\Frontend\AST\Support\DependentNameProvider;
use Railt\SDL\Frontend\AST\Support\DescriptionProvider;
use Railt\SDL\Frontend\Context;
use Railt\SDL\Frontend\IR\Opcode\AttachOpcode;
use Railt\SDL\Frontend\IR\Opcode\DefineOpcode;
use Railt\SDL\Frontend\IR\Opcode\DescriptionOpcode;
use Railt\SDL\Frontend\IR\Value\TypeValue;

/**
 * Class DependentTypeDefinitionNode
 */
abstract class DependentTypeDefinitionNode extends Rule implements ProvidesType, ProvidesName, ProvidesOpcode, ProvidesDescription
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

    /**
     * @param Context $context
     * @return iterable
     */
    public function getOpcodes(Context $context): iterable
    {
        $parent  = $context->create();
        $current = yield new DefineOpcode($this->getNameValue(), new TypeValue($this->getType()));

        yield new AttachOpcode($current, $parent);

        if ($description = $this->getDescriptionValue()) {
            yield new AttachOpcode(yield new DescriptionOpcode($description), $current);
        }
    }
}
