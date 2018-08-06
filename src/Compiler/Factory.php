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
use Railt\Reflection\Contracts\Invocation\DirectiveInvocation;
use Railt\Reflection\Document;
use Railt\SDL\Compiler\Builder\BuilderInterface;
use Railt\SDL\Compiler\Builder\Definition;
use Railt\SDL\Compiler\Builder\Dependent;
use Railt\SDL\Compiler\Builder\Invocation;
use Railt\SDL\Exception\CompilerException;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @var BuilderInterface[]
     */
    private const NODE_MAPPINGS = [
        'ObjectDefinition'    => Definition\ObjectBuilder::class,
        'InterfaceDefinition' => Definition\InterfaceBuilder::class,

        'FieldDefinition'    => Dependent\FieldBuilder::class,
        'ArgumentDefinition' => Dependent\ArgumentBuilder::class,

        'Directive'         => Invocation\DirectiveBuilder::class,
        'DirectiveArgument' => Invocation\DirectiveArgumentBuilder::class,
    ];

    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @var Document
     */
    private $document;

    /**
     * @var RuleInterface
     */
    private $ast;

    /**
     * Processor constructor.
     * @param Document $document
     * @param RuleInterface $ast
     */
    public function __construct(Document $document, RuleInterface $ast)
    {
        $this->ast      = $ast;
        $this->document = $document;
        $this->pipeline = new Pipeline();
    }

    /**
     * @return Document
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function process(): Document
    {
        foreach ($this->ast as $child) {
            $definition = $this->build($child, $this->document);

            if ($definition instanceof DefinitionInterface\TypeDefinition) {
                $this->document->withDefinition($definition);
            }

            if ($definition instanceof DirectiveInvocation) {
                $this->document->withDirective($definition);
            }
        }

        foreach ($this->pipeline as $next) {
            $next();
        }

        return $this->document;
    }

    /**
     * @param RuleInterface $rule
     * @param DefinitionInterface $parent
     * @return DefinitionInterface
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function build(RuleInterface $rule, DefinitionInterface $parent): DefinitionInterface
    {
        $mapping = self::NODE_MAPPINGS[$rule->getName()] ?? null;

        if ($mapping === null) {
            throw (new CompilerException(\sprintf('No mappings found for %s AST', $rule->getName())))
                ->throwsIn($this->document->getFile(), $rule->getOffset());
        }

        /** @var BuilderInterface $instance */
        $instance = new $mapping($this->pipeline, $this);

        return $instance->build($rule, $parent);
    }
}
