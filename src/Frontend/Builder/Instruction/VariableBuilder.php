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
use Railt\SDL\IR\SymbolTable\VarSymbol;

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
        $isConstant = $this->isConstant($rule);
        $variables  = [];

        foreach ($this->getVariableNames($rule) as $variable) {
            $record = $variables[] = $ctx->declare($variable);

            $isConstant ? $record->lock() : $record->unlock();
        }

        yield function () use ($rule, $variables) {
            /** @var ValueInterface $value */
            $value = yield $this->getValueNode($rule);

            /** @var VarSymbol $variable */
            foreach ($variables as $variable) {
                $variable->set($value);
            }
        };
    }

    /**
     * @param RuleInterface $rule
     * @return iterable|string[]
     */
    private function getVariableNames(RuleInterface $rule): iterable
    {
        foreach ($rule->find('> #VariableName') as $name) {
            yield $name->first('> :T_VARIABLE')->getValue(1);
        }
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
