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
use Railt\SDL\Frontend\AST\TypeNameNode;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\Frontend\Record\Argument;
use Railt\SDL\Frontend\Record\Record;
use Railt\SDL\Frontend\Record\RecordInterface;
use Railt\SDL\Frontend\Type\Anonymous;
use Railt\SDL\Frontend\Type\Type;
use Railt\SDL\Frontend\Type\TypeInterface;
use Railt\SDL\Frontend\Type\TypeName;
use Railt\SDL\Frontend\Type\TypeNameInterface;

/**
 * Class DefinitionBuilder
 */
class DefinitionBuilder extends BaseBuilder
{
    /**
     * @var string[]
     */
    private const TYPE_DEFINITIONS = [
        'ObjectDefinition'    => Type::OBJECT,
        'SchemaDefinition'    => Type::SCHEMA,
        'DirectiveDefinition' => Type::DIRECTIVE,
    ];

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return (bool)(self::TYPE_DEFINITIONS[$rule->getName()] ?? false);
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return \Generator|RecordInterface|TypeNameInterface|void
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule)
    {
        $name = yield $this->createTypeName($rule);

        $record = new Record($name, $this->getType($rule), $rule);

        foreach ($this->createTypeArguments($ctx, $rule) as $argument) {
            $record->addArgument($argument);
        }

        $ctx->close();

        return $record;
    }

    /**
     * @param RuleInterface $rule
     * @return TypeNameInterface
     */
    private function createTypeName(RuleInterface $rule): TypeNameInterface
    {
        /** @var TypeNameNode $node */
        $node = $rule->first('> #TypeDefinitionHeader > #TypeName');

        return $node ? $node->toTypeName() : new Anonymous();
    }

    /**
     * @param RuleInterface $rule
     * @return TypeInterface
     */
    private function getType(RuleInterface $rule): TypeInterface
    {
        $type = TypeName::fromString(self::TYPE_DEFINITIONS[$rule->getName()]);

        return Type::of($type);
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return iterable
     */
    private function createTypeArguments(ContextInterface $ctx, RuleInterface $rule): iterable
    {
        $arguments = $rule->find('> #TypeDefinitionHeader > #TypeArgumentDefinition');

        foreach ($arguments as $argument) {
            $name = $argument->first('> #Name > :T_NAME')->getValue();
            /** @var TypeNameNode $type */
            $type = $argument->first('> #TypeName');

            yield new Argument($name, Type::of($type->toTypeName()), $ctx);
        }
    }
}
