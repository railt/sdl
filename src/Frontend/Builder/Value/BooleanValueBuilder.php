<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Value;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\SymbolTable\Value;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type;

/**
 * Class BooleanValueBuilder
 */
class BooleanValueBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'BooleanValue';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return ValueInterface
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule): ValueInterface
    {
        /** @var LeafInterface $leaf */
        $leaf = $rule->getChild(0);

        return new Value($leaf->getValue() === 'true', Type::bool());
    }
}
