<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Instruction;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\SymbolTable\ValueInterface;

/**
 * Class VariableBuilder
 */
class VariableBuilder extends BaseBuilder
{
    /**
     * @var string[]
     */
    private const VARIABLE_DEFINITIONS = [
        'ConstantDefinition',
        'VariableDefinition',
    ];

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return \in_array($rule->getName(), self::VARIABLE_DEFINITIONS, true);
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return \Generator|\Closure
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule): \Generator
    {
        yield function() use ($ctx, $rule): \Generator {
            /** @var ValueInterface $value */
            [$isConstant, $value] = [$this->isConstant($rule), yield $this->getValueNode($rule)];

            foreach ($rule->find('> #VariableName') as $name) {
                $variable = $name->first('> :T_VARIABLE')->getValue(1);

                $record = $ctx->declare($variable)->set($value);

                $isConstant ? $record->lock() : $record->unlock();
            }
        };
    }

    /**
     * @param RuleInterface $rule
     * @return RuleInterface
     */
    private function getValueNode(RuleInterface $rule): RuleInterface
    {
        return $rule->first('> #VariableValue')->getChild(0);
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    private function isConstant(RuleInterface $rule): bool
    {
        return $rule->getName() === 'ConstantDefinition';
    }
}
