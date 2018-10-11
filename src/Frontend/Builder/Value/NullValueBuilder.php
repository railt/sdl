<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Value;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Value\AstValueInterface;
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\SymbolTable\Value;
use Railt\SDL\IR\Type;

/**
 * Class NullValueBuilder
 */
class NullValueBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'NullValue';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface|AstValueInterface $rule
     * @return mixed|Value
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule)
    {
        return new Value(null, Type::null());
    }
}
