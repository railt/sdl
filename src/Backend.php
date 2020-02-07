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
use Railt\SDL\Backend\Context;
use Railt\SDL\Backend\HashTableInterface;
use Railt\SDL\Backend\Linker\LinkerInterface;
use Railt\SDL\Backend\Linker\LinkerVisitor;
use Railt\SDL\Backend\NameResolver\HumanReadableResolver;
use Railt\SDL\Backend\NameResolver\NameResolverInterface;
use Railt\SDL\Backend\TypeBuilderVisitor;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Spec\SpecificationInterface;

/**
 * Class Backend
 */
class Backend
{
    /**
     * @var Context
     */
    private Context $context;

    /**
     * @var LinkerInterface
     */
    private LinkerInterface $linker;

    /**
     * @var NameResolverInterface
     */
    private NameResolverInterface $resolver;

    /**
     * @var SpecificationInterface
     */
    private SpecificationInterface $spec;

    /**
     * Backend constructor.
     *
     * @param Compiler $compiler
     * @param Context $session
     */
    public function __construct(Compiler $compiler, Context $session)
    {
        $this->resolver = $compiler->getNameResolver();
        $this->context = $session;
        $this->linker = $compiler->getLinker();
        $this->spec = $compiler->getSpecification();
    }

    /**
     * @param iterable|Node[] $ast
     * @param HashTableInterface $vars
     * @return SchemaInterface
     * @throws \Throwable
     */
    public function run(iterable $ast, HashTableInterface $vars): SchemaInterface
    {
        // Apply specification logic
        $ast = $this->adoptSpecification($ast);

        // Build types and move them into Context
        $ast = $this->buildTypes($ast, $vars);

        // Apply linker to all loaded types in Context
        $this->linkTypes($ast);

        return $this->context->getSchema();
    }

    /**
     * @param iterable|Node[] $ast
     * @return iterable|Node[]
     */
    private function adoptSpecification(iterable $ast): iterable
    {
        return $this->spec->execute($ast);
    }

    /**
     * @param iterable $ast
     * @param HashTableInterface $vars
     * @return iterable
     */
    private function buildTypes(iterable $ast, HashTableInterface $vars): iterable
    {
        $buildTypes = new TypeBuilderVisitor($vars, $this->resolver, $this->context);

        return (new Traverser([$buildTypes]))->traverse($ast);
    }

    /**
     * @param iterable $ast
     * @return iterable
     */
    private function linkTypes(iterable $ast): iterable
    {
        $traverser = new Traverser([
            new LinkerVisitor($this->context, $this->linker),
        ]);

        return $traverser->traverse($ast);
    }
}
