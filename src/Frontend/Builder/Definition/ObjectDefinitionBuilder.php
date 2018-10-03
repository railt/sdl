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
use Railt\SDL\Frontend\Deferred\NamedDeferred;
use Railt\SDL\Frontend\Definition\DefinitionInterface;
use Railt\SDL\IR\Definition\InterfaceDefinitionValueObject;
use Railt\SDL\IR\Definition\ObjectDefinitionValueObject;
use Railt\SDL\IR\DefinitionValueObject;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\ValueObject;

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
        /** @var DefinitionInterface $definition */
        $definition = yield $rule->first('> #TypeDefinition');

        yield new NamedDeferred($definition, $ctx, function (ContextInterface $local) use ($definition, $rule) {
            $struct = new ObjectDefinitionValueObject();

            $struct->type = Type::object($definition->getName()->getFullyQualifiedName());
            $struct->name = $definition->getName();
            $struct->file = $local->getFile();

            yield from $this->loadInterfaces($local, $struct, $rule);

            return $struct;
        });
    }

    /**
     * @param ContextInterface $local
     * @param ObjectDefinitionValueObject $vo
     * @param RuleInterface $rule
     * @return \Generator
     * @throws \Railt\SDL\Exception\NotFoundException
     */
    private function loadInterfaces(ContextInterface $local, ObjectDefinitionValueObject $vo, RuleInterface $rule): \Generator
    {
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
