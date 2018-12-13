<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition as DefinitionInterface;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Builder\BuilderInterface;
use Railt\SDL\Builder\Context;
use Railt\SDL\Builder\Definition;
use Railt\SDL\Builder\Invocation;
use Railt\SDL\Exception\TypeException;

/**
 * Class Backend
 */
class Backend
{
    /**
     * @var string
     */
    private const ERR_UNSUPPORTED_RULE = '%s is not supported by this compiler';

    /**
     * @var string
     */
    private const ERR_UNRECOGNIZED_RULE = 'The language rule <%s> is not supported or implemented by this compiler';

    /**
     * @var string[]
     */
    private const BUILDERS = [
        // Root definitions
        'SchemaDefinition'    => Definition\SchemaBuilder::class,
        'ObjectDefinition'    => Definition\ObjectBuilder::class,
        'EnumDefinition'      => Definition\EnumBuilder::class,
        'ScalarDefinition'    => Definition\ScalarBuilder::class,
        'InputDefinition'     => Definition\InputBuilder::class,
        'DirectiveDefinition' => Definition\DirectiveBuilder::class,
        'InterfaceDefinition' => Definition\InterfaceBuilder::class,
        'UnionDefinition'     => Definition\UnionBuilder::class,
        // Invocations
        'Directive'           => Invocation\DirectiveBuilder::class,
    ];

    /**
     * @var string[]
     */
    private const UNSUPPORTED_BUILDERS = [
        'QueryOperation'        => 'Query expression',
        'MutationOperation'     => 'Mutation expression',
        'SubscriptionOperation' => 'Subscription expression',
        'FragmentDefinition'    => 'Fragment definition',
    ];

    /**
     * @var Process
     */
    private $process;

    /**
     * Backend constructor.
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * @param Document $document
     * @return Context
     */
    public function context(Document $document): Context
    {
        return new Context($document->getFile(), $document);
    }

    /**
     * @param Context $context
     * @param RuleInterface $ast
     * @return Document
     * @throws TypeException
     */
    public function each(Context $context, RuleInterface $ast): Document
    {
        /** @var \Railt\Reflection\Document $document */
        $document = $context->getDocument();

        foreach ($ast->getChildren() as $child) {
            $definition = $this->exec($context, $child);

            $document->withDefinition($definition);
        }

        return $document;
    }

    /**
     * @param Context $context
     * @param RuleInterface $ast
     * @return DefinitionInterface
     * @throws TypeException
     */
    public function exec(Context $context, RuleInterface $ast): DefinitionInterface
    {
        if ($builder = self::BUILDERS[$ast->getName()] ?? null) {
            return $this->execBuilder(new $builder($context, $ast, $this->process));
        }

        if ($unsupported = self::UNSUPPORTED_BUILDERS[$ast->getName()] ?? null) {
            $exception = new TypeException(\sprintf(self::ERR_UNSUPPORTED_RULE, $unsupported));
            $exception->throwsIn($context->getFile(), $ast->getOffset());

            throw $exception;
        }

        $exception = new TypeException(\sprintf(self::ERR_UNRECOGNIZED_RULE, $ast->getName()));
        $exception->throwsIn($context->getFile(), $ast->getOffset());

        throw $exception;
    }

    /**
     * @param BuilderInterface $builder
     * @return DefinitionInterface
     */
    private function execBuilder(BuilderInterface $builder): DefinitionInterface
    {
        return $builder->build();
    }
}
