<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Definition;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Deferred\DeferredInterface;
use Railt\SDL\Frontend\Definition\DefinitionInterface;
use Railt\SDL\Frontend\Definition\InvocationInterface;
use Railt\SDL\IR\Definition\InterfaceDefinitionValueObject;
use Railt\SDL\IR\Definition\ObjectDefinitionValueObject;
use Railt\SDL\IR\DefinitionValueObject;
use Railt\SDL\IR\SymbolTable\ValueInterface;

/**
 * Class ObjectDefinitionBuilder
 */
class ObjectDefinitionBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'ObjectDefinition';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return mixed|\Generator|void
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule)
    {
        /** @var DeferredInterface $definition */
        $definition = yield $rule->first('> #TypeDefinition');

        $definition->then(function (DefinitionInterface $definition, InvocationInterface $from) {
            /** @var ContextInterface $local */
            $local = yield $definition->getLocalContext(); // Open local context

            // $message = "Call type '%s'\n - Defined in %s\n - From %s";
            // $message = \sprintf($message, $definition->getName(), $local, $from->getContext());

            //echo $message;die;

            //yield $definition->getContext(); // Close
        });
    }

    /**
     * @param ContextInterface $local
     * @param ObjectDefinitionValueObject $vo
     * @param RuleInterface $rule
     * @return \Generator
     * @throws \Railt\SDL\Exception\NotFoundException
     */
    private function loadInterfaces(
        ContextInterface $local,
        ObjectDefinitionValueObject $vo,
        RuleInterface $rule
    ): \Generator {
        foreach ($rule->find('> #TypeDefinitionImplements') as $impl) {
            /** @var ValueInterface $result */
            $result = yield $impl->getChild(0);

            yield from $iterator = $this->invoke($result->getValue(), $local);

            /** @var InterfaceDefinitionValueObject $interface */
            $interface = $this->loadInterface($iterator->getReturn());

            $vo->implements[] = $interface->name;
        }
    }

    private function loadInterface(DefinitionValueObject $dto)
    {
        return $dto;
    }
}
