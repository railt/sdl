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
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\Type\Name;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class ConstantValueBuilder
 */
class ConstantValueBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'ConstantValue';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return TypeNameInterface
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule): TypeNameInterface
    {
        return Name::fromString($rule->getChild(0)->getValue());
    }
}
