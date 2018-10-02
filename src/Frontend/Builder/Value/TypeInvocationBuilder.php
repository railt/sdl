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
use Railt\SDL\IR\SymbolTable\TypeInvocationPrimitive;
use Railt\SDL\IR\SymbolTable\Value;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\Type\Name;

/**
 * Class TypeInvocationBuilder
 */
class TypeInvocationBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'TypeInvocation';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return \Generator|ValueInterface
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule): \Generator
    {
        /** @var ValueInterface $value */
        $value = yield $rule->first('> #GenericInvocationName')->getChild(0);

        $name = Name::new((string)$value->getValue());

        // TODO Add arguments

        return new Value(new TypeInvocationPrimitive($name, $ctx), Type::type());
    }
}
