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
use Railt\SDL\Exception\TypeConflictException;
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Definition\Invocation;
use Railt\SDL\Frontend\Definition\InvocationInterface;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\Type\Name;
use Railt\SDL\IR\Type\TypeNameInterface;

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
     * @return \Generator|InvocationInterface
     * @throws TypeConflictException
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule): \Generator
    {
        /** @var ValueInterface $value */
        $value = yield $rule->first('> #GenericInvocationName')->getChild(0);

        $primitive = clone $this->resolveName($ctx, $value);

        foreach ($rule->find('> #GenericInvocationArgument') as $argument) {
            yield from $n = $this->fetchArgumentName($ctx, $argument);
            yield from $v = $this->fetchArgumentValue($ctx, $argument);

            /** @var TypeNameInterface $argumentName */
            $argumentName = $n->getReturn();

            /** @var ValueInterface $argumentValue */
            $argumentValue = $v->getReturn();

            \var_dump(__METHOD__, $argumentName, $argumentValue);

            $primitive->addArgument($argumentName->getFullyQualifiedName(), $argumentValue->getValue());
        }

        return $primitive;
    }

    /**
     * @param ContextInterface $ctx
     * @param TypeNameInterface|ValueInterface $name
     * @return InvocationInterface
     */
    private function resolveName(ContextInterface $ctx, $name): InvocationInterface
    {
        if ($name instanceof TypeNameInterface) {
            return new Invocation($name, $ctx);
        }

        $isConst = $name->getType()->typeOf(Type::const());
        $isType  = $name->getType()->typeOf(Type::type());

        switch (true) {
            case $isConst:
                return new Invocation(Name::fromString((string)$name->getValue()), $ctx);

            case $isType:
                /** @var Invocation $value */
                return $name->getValue();
        }

        $error = 'Type name should be valid type name, but %s given';
        throw new TypeConflictException(\sprintf($error, $name));
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return \Generator|TypeNameInterface
     * @throws TypeConflictException
     */
    private function fetchArgumentName(ContextInterface $ctx, RuleInterface $rule): \Generator
    {
        /** @var TypeNameInterface|ValueInterface $name */
        $name = yield $rule->first('> #GenericInvocationArgumentName')->getChild(0);

        if ($name instanceof TypeNameInterface) {
            return $name;
        }

        $isConst = $name->getType()->typeOf(Type::const());

        if (! $isConst) {
            $error     = 'Generic argument name must be a const but %s given';
            $exception = new TypeConflictException(\sprintf($error, $name));
            $exception->throwsIn($ctx->getFile(), $rule->getOffset());

            throw $exception;
        }

        return Name::fromString((string)$name->getValue());
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return \Generator|ValueInterface
     */
    private function fetchArgumentValue(ContextInterface $ctx, RuleInterface $rule): \Generator
    {
        /** @var ValueInterface $value */
        $value = yield $rule->first('> #GenericInvocationArgumentValue')->getChild(0);

        $isType = $value->getType()->typeOf(Type::type());

        if (! $isType) {
            $error     = 'Generic argument value must be a valid type, but %s given';
            $exception = new TypeConflictException(\sprintf($error, $value));
            $exception->throwsIn($ctx->getFile(), $rule->getOffset());
        }

        return $value;
    }
}
