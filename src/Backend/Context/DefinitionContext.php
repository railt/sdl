<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Phplrt\Visitor\Traverser;
use Railt\SDL\Backend\Context\Support\DescriptionReaderTrait;
use Railt\SDL\Backend\Context\Support\TypeReferenceTrait;
use Railt\SDL\Backend\Context\Support\ValueTrait;
use Railt\SDL\Backend\HashTable\VariablesVisitor;
use Railt\SDL\Backend\HashTableInterface;
use Railt\SDL\Frontend\Ast\DefinitionNode;

/**
 * Class DefinitionContext
 */
abstract class DefinitionContext implements DefinitionContextInterface
{
    use ValueTrait;
    use TypeReferenceTrait;
    use DescriptionReaderTrait;

    /**
     * @var DefinitionNode
     */
    protected DefinitionNode $ast;

    /**
     * @var Factory
     */
    private Factory $factory;

    /**
     * DefinitionContext constructor.
     *
     * @param HashTableInterface $vars
     * @param Factory $factory
     * @param DefinitionNode $ast
     */
    public function __construct(HashTableInterface $vars, Factory $factory, DefinitionNode $ast)
    {
        $this->factory = $factory;
        $this->ast = $this->prebuild($vars, $ast);
    }

    /**
     * @param HashTableInterface $vars
     * @param DefinitionNode $ast
     * @return DefinitionNode|iterable
     */
    private function prebuild(HashTableInterface $vars, DefinitionNode $ast): DefinitionNode
    {
        $traverser = new Traverser([
            new VariablesVisitor($vars),
        ]);

        return $traverser->traverse($ast);
    }

    /**
     * @return Factory
     */
    protected function getFactory(): Factory
    {
        return $this->factory;
    }
}
