<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Exception\InvalidArgumentException;
use Railt\SDL\Frontend\AST\TypeNameNode;
use Railt\SDL\Frontend\Builder\Definition\Definition;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Matcher;
use Railt\SDL\Frontend\Parser;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\Type\Name;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class TypeDefinitionHeaderBuilder
 */
class TypeDefinitionBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'TypeDefinition';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return \Generator|Definition
     * @throws InvalidArgumentException
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule): \Generator
    {
        /** @var TypeNameInterface $name */
        $name = yield $rule->first('> #TypeName');

        $definition = new Definition($ctx, $name);

        foreach ($rule->find('> #GenericDefinitionArgument') as $argument) {
            yield from $name = $this->getArgumentName($argument);
            yield from $value = $this->getArgumentValue($argument);

            $definition->addArgument((string)$name->getReturn(), $value->getReturn());
        }

        return $definition;
    }

    /**
     * @param RuleInterface $argument
     * @return \Generator|TypeNameInterface
     */
    private function getArgumentName(RuleInterface $argument): \Generator
    {
        /** @var TypeNameInterface $name */
        return yield $argument->first('> #GenericDefinitionArgumentName')->getChild(0);
    }

    /**
     * @param RuleInterface $argument
     * @return \Generator|TypeNameInterface
     */
    private function getArgumentValue(RuleInterface $argument): \Generator
    {
        /** @var ValueInterface|TypeNameInterface $value */
        return yield $argument->first('> #GenericDefinitionArgumentValue')->getChild(0);
    }
}
