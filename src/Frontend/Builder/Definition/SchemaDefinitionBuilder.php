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
use Railt\SDL\Frontend\Deferred\Deferred;
use Railt\SDL\Frontend\Deferred\DeferredInterface;
use Railt\SDL\Frontend\Deferred\NamedDeferred;
use Railt\SDL\IR\SymbolTable\Value;
use Railt\SDL\IR\SymbolTable\VarSymbolInterface;
use Railt\SDL\IR\Type;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class SchemaDefinitionBuilder
 */
class SchemaDefinitionBuilder extends BaseBuilder
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'SchemaDefinition';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return \Generator|mixed
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule)
    {
        /** @var TypeNameInterface|null $name */
        $name = yield $rule->first('> #TypeName');

        yield $this->deferred($ctx, yield $rule->first('> #TypeName'))
            ->then(function(ContextInterface $context) use ($name, $rule) {
                return $this->then($context, $rule, $name);
            });
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @param null|TypeNameInterface $name
     */
    private function then(ContextInterface $ctx, RuleInterface $rule, ?TypeNameInterface $name)
    {
        /** @var VarSymbolInterface $a */
        // $a = yield 'a';

        // dd($a->set(new Value(24, Type::int())));
    }

    /**
     * @param ContextInterface $context
     * @param null|TypeNameInterface $name
     * @return DeferredInterface
     */
    private function deferred(ContextInterface $context, ?TypeNameInterface $name): DeferredInterface
    {
        return $name ? new NamedDeferred($name, $context) : new Deferred($context);
    }
}
