<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Definition;

use Railt\SDL\Exception\NotFoundException;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Deferred\Deferred;
use Railt\SDL\Frontend\Deferred\DeferredInterface;
use Railt\SDL\Frontend\Map;
use Railt\SDL\IR\Definition\DefinitionValueObject;
use Railt\SDL\IR\Type\TypeNameInterface;
use Railt\SDL\Naming\StrategyInterface;

/**
 * Class Storage
 */
class Storage
{
    /**
     * @var array|DefinitionInterface[]
     */
    private $definitions;

    /**
     * @var array|DeferredInterface[]
     */
    private $resolvers;

    /**
     * @var DefinitionValueObject[]
     */
    private $resolved = [];

    /**
     * @var StrategyInterface
     */
    private $naming;

    /**
     * Pipeline constructor.
     * @param StrategyInterface $naming
     */
    public function __construct(StrategyInterface $naming)
    {
        $this->naming = $naming;

        $this->definitions = new Map(function (TypeNameInterface $name): string {
            return $name->getFullyQualifiedName();
        });

        $this->resolvers = new Map(function (DefinitionInterface $definition): string {
            return $definition->getName()->getFullyQualifiedName();
        });
    }

    /**
     * @param DefinitionInterface $definition
     * @param DeferredInterface $deferred
     * @return $this|Storage
     */
    public function remember(DefinitionInterface $definition, DeferredInterface $deferred): self
    {
        $this->resolvers[$definition]              = $deferred;
        $this->definitions[$definition->getName()] = $definition;

        return $this;
    }

    /**
     * @param InvocationInterface $invocation
     * @return \Generator|mixed
     */
    public function invoke(InvocationInterface $invocation)
    {
        $name = $this->naming->resolve($invocation->getName(), $invocation->getArguments());

        if ($this->isResolved($name)) {
            return $this->resolved[$name];
        }

        [$definition, $deferred] = $this->get($invocation->getName(), $invocation->getContext());

        return $deferred->invoke($definition, $invocation);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isResolved(string $name): bool
    {
        return isset($this->resolved[$name]);
    }

    /**
     * @param TypeNameInterface $name
     * @param ContextInterface $ctx
     * @return array
     */
    private function get(TypeNameInterface $name, ContextInterface $ctx): array
    {
        $result = $this->find($name) ?? $this->find($name->in($ctx->getName()));

        if ($result === null) {
            $error = 'Type %s not found or could not be loaded';
            throw new NotFoundException(\sprintf($error, $name));
        }

        return $result;
    }

    /**
     * @param TypeNameInterface $name
     * @return array|null
     */
    private function find(TypeNameInterface $name): ?array
    {
        if (isset($this->definitions[$name])) {
            $definition = $this->definitions[$name];

            return [$definition, $this->resolvers[$definition]];
        }

        return null;
    }

    /**
     * @param InvocationInterface $name
     * @return bool
     */
    public function resolved(InvocationInterface $name): bool
    {
        return $this->isResolved($this->naming->resolve($name->getName(), $name->getArguments()));
    }

    /**
     * @param \Closure $filter
     * @return \Generator|DeferredInterface[]
     */
    public function export(\Closure $filter): \Generator
    {
        /**
         * @var DefinitionInterface $definition
         * @var DeferredInterface $then
         */
        foreach ($this->resolvers as $definition => $then) {
            if ($filter($definition)) {
                yield $this->toInvocationDeferred($definition, $then);
            }
        }
    }

    /**
     * @param DefinitionInterface $def
     * @param DeferredInterface $then
     * @return DeferredInterface
     */
    private function toInvocationDeferred(DefinitionInterface $def, DeferredInterface $then): DeferredInterface
    {
        return new Deferred($this->toInvocationDeferredCallback($def, $then));
    }

    /**
     * @param DefinitionInterface $original
     * @param DeferredInterface $then
     * @return \Closure
     */
    private function toInvocationDeferredCallback(DefinitionInterface $original, DeferredInterface $then): \Closure
    {
        return function (DefinitionInterface $definition = null, InvocationInterface $invocation = null) use (
            $original,
            $then
        ) {
            $definition = $definition ?? $original;
            $invocation = $invocation ?? new Invocation($definition->getName(), $definition->getContext());

            return $then->invoke($definition, $invocation);
        };
    }
}
