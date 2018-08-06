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

/**
 * Class FieldDefinitionNode
 */
class FieldDefinitionNode extends Rule
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
    public function getFieldName(): string
    {
        return $this->first('T_NAME', 1)->getValue();
    }

    /**
     * @return iterable|ArgumentDefinitionNode[]
     */
    public function getArguments(): iterable
    {
        $arguments = $this->first('FieldArguments', 1);

        if ($arguments) {
            /** @var RuleInterface $argument */
            foreach ($arguments as $argument) {
                yield $argument;
            }
        }
    }
}
