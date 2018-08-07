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
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Ast\Common\DescriptionProvider;
use Railt\SDL\Compiler\Ast\Common\DirectivesProvider;
use Railt\SDL\Compiler\Ast\TypeHintNode;
use Railt\SDL\Compiler\Ast\Value\ValueInterface;

/**
 * Class InputFieldDefinitionNode
 */
class InputFieldDefinitionNode extends Rule
{
    use DirectivesProvider;
    use DescriptionProvider;

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        /** @var RuleInterface $fieldName */
        $fieldName = $this->first('InputFieldName', 1);

        return $fieldName->getChild(0)->getValue();
    }

    /**
     * @return null|TypeHintNode|NodeInterface
     */
    public function getTypeHint(): TypeHintNode
    {
        return $this->first('TypeHint', 1);
    }

    /**
     * @return null|ValueInterface|NodeInterface
     */
    public function getDefaultValue(): ?ValueInterface
    {
        return $this->first('Value', 1);
    }
}
