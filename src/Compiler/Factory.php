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
use Railt\Reflection\Document;
use Railt\SDL\Compiler\Processor\Definition;
use Railt\SDL\Compiler\Processor\Extension;
use Railt\SDL\Compiler\Processor\Invocation;
use Railt\SDL\Compiler\Processor\Processable;
use Railt\SDL\Exception\CompilerException;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @var Processable[]
     */
    private const NODE_MAPPINGS = [
        'DirectiveDefinition'  => Definition\DirectiveProcessor::class,
        'Directive'            => Invocation\DirectiveInvocationProcessor::class,
        'EnumDefinition'       => Definition\EnumProcessor::class,
        'EnumExtension'        => Extension\EnumExtensionProcessor::class,
        'InputDefinition'      => Definition\InputProcessor::class,
        'InputExtension'       => Extension\InputExtensionProcessor::class,
        'InputUnionDefinition' => Definition\InputUnionProcessor::class,
        'InputUnionExtension'  => Extension\InputUnionExtensionProcessor::class,
        'InterfaceDefinition'  => Definition\InterfaceProcessor::class,
        'InterfaceExtension'   => Extension\InterfaceExtensionProcessor::class,
        'ObjectDefinition'     => Definition\ObjectProcessor::class,
        'ObjectExtension'      => Extension\ObjectExtensionProcessor::class,
        'ScalarDefinition'     => Definition\ScalarProcessor::class,
        'ScalarExtension'      => Extension\ScalarExtensionProcessor::class,
        'SchemaDefinition'     => Definition\SchemaProcessor::class,
        'SchemaExtension'      => Extension\SchemaExtensionProcessor::class,
        'UnionDefinition'      => Definition\UnionProcessor::class,
        'UnionExtension'       => Extension\UnionExtensionProcessor::class,
    ];

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var CallStack
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
        $this->ast        = $ast;
        $this->document   = $document;
        $this->stack      = new CallStack();
        $this->pipeline   = new Pipeline();
    }

    /**
     * @return Document
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function process(): Document
    {
        $this->build()->pipeline->reduce(function (\Closure $callback): void {
            $callback();
        });

        return $this->document;
    }

    /**
     * @return Factory|$this
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    protected function build(): self
    {
        /** @var RuleInterface $child */
        foreach ($this->ast as $child) {
            $processor = $this->findProcessor($child->getName());

            if ($processor === null) {
                throw (new CompilerException(\sprintf('Unprocessable node %s', $child->getName())))
                    ->throwsIn($this->document->getFile(), $child->getOffset());
            }

            if ($definition = $processor->process($child)) {
                $this->document->withDefinition($definition);
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @return null|Processable
     */
    private function findProcessor(string $name): ?Processable
    {
        $processor = self::NODE_MAPPINGS[$name] ?? null;

        return $processor ? new $processor($this->pipeline, $this->stack, $this->document) : null;
    }
}
