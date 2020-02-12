<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL;

use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Phplrt\Visitor\Traverser;
use Phplrt\Visitor\TraverserInterface;
use Railt\SDL\Backend\ContextInterface;
use Railt\SDL\Backend\HashTable\VariablesVisitor;
use Railt\SDL\Backend\HashTableInterface;
use Railt\SDL\Backend\Linker\LinkerVisitor;
use Railt\SDL\Backend\SymbolTableBuilderVisitor;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class Backend
 */
class Backend
{
    /**
     * @var Compiler
     */
    private Compiler $compiler;

    /**
     * @var TraverserInterface
     */
    private TraverserInterface $traverser;

    /**
     * @var ContextInterface
     */
    private ContextInterface $ctx;

    /**
     * Backend constructor.
     *
     * @param Compiler $compiler
     * @param ContextInterface $ctx
     */
    public function __construct(Compiler $compiler, ContextInterface $ctx)
    {
        $this->ctx = $ctx;
        $this->compiler = $compiler;

        $this->traverser = new Traverser([
            $compiler->getSpecification(),
            new SymbolTableBuilderVisitor($ctx),
        ]);
    }

    /**
     * @param iterable|Node[] $ast
     * @param HashTableInterface $vars
     * @return SchemaInterface
     * @throws \Throwable
     */
    public function run(iterable $ast, HashTableInterface $vars): SchemaInterface
    {
        // Precompile
        $ast = $this->precompile($ast, $vars);

        // Link types
        $ast = $this->linker($ast);

        return $this->ctx->getSchema();
    }

    /**
     * @param iterable $ast
     * @param HashTableInterface $vars
     * @return iterable
     */
    private function precompile(iterable $ast, HashTableInterface $vars): iterable
    {
        return (clone $this->traverser)
            ->with(new VariablesVisitor($vars))
            ->traverse($ast);
    }

    /**
     * @param iterable $ast
     * @return iterable
     */
    private function linker(iterable $ast): iterable
    {
        $traverser = new Traverser([
            new LinkerVisitor($this->ctx, $this->compiler->getLinker()),
        ]);

        return $traverser->traverse($ast);
    }
}
