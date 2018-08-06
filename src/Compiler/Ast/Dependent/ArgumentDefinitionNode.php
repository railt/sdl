<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Dependent;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\Rule;
use Railt\SDL\Compiler\Ast\Common\DescriptionProvider;
use Railt\SDL\Compiler\Ast\Common\DirectivesProvider;
use Railt\SDL\Compiler\Ast\TypeHintNode;
use Railt\SDL\Compiler\Ast\Value\ValueInterface;
use Railt\SDL\Compiler\Ast\Value\ValueNode;

/**
 * Class ArgumentDefinitionNode
 */
class ArgumentDefinitionNode extends Rule
{
    use DescriptionProvider;
    use DirectivesProvider;

    /**
     * @return TypeHintNode|NodeInterface
     */
    public function getTypeHint(): TypeHintNode
    {
        return $this->first('TypeHint', 1);
    }

    /**
     * @return string
     */
    public function getArgumentName(): string
    {
        return $this->first('T_NAME', 1)->getValue();
    }

    /**
     * @return null|ValueInterface|NodeInterface
     */
    public function getDefaultValue(): ?ValueInterface
    {
        /** @var ValueNode $value */
        $value = $this->first('Value', 1);

        return $value ? $value->getInnerValue() : null;
    }
}
