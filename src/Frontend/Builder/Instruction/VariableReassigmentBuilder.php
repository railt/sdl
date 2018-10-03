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
 * Class VariableReassigmentBuilder
 */
class VariableReassigmentBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'VariableReassigment';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return \Generator|\Closure
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule)
    {
        yield function() use ($ctx, $rule): \Generator {
            /** @var ValueInterface $value */
            $value = yield $rule->first('> #VariableValue')->getChild(0);

            foreach ($rule->find('> #VariableName') as $child) {
                $name = $child->first('> :T_VARIABLE')->getValue(1);

                $ctx->fetch($name)->set($value);
            }
        };
    }
}
