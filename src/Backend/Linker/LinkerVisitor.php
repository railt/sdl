<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Linker;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Phplrt\Visitor\Visitor;
use Railt\SDL\Backend\Context\Context;
use Railt\SDL\Backend\Context\ContextInterface;
use Railt\SDL\Exception\TypeNotFoundException;
use Railt\SDL\Frontend\Ast\Definition\Type\TypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Identifier;
use Railt\SDL\Frontend\Ast\Type\NamedDirectiveNode;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;
use Railt\SDL\Frontend\Ast\TypeName;
use Railt\TypeSystem\Schema;

/**
 * Class LinkerVisitor
 */
class LinkerVisitor extends Visitor
{
    /**
     * @var string
     */
    private const ERROR_TYPE_NOT_FOUND = 'Type "%s" not found or could not be loaded';

    /**
     * @var string
     */
    private const ERROR_DIRECTIVE_NOT_FOUND = 'Directive "@%s" not found or could not be loaded';

    /**
     * @var ContextInterface
     */
    private ContextInterface $context;

    /**
     * @var LinkerInterface
     */
    private LinkerInterface $linker;

    /**
     * @var array|string[]
     */
    private array $genericArguments = [];

    /**
     * LinkerVisitor constructor.
     *
     * @param ContextInterface $context
     * @param LinkerInterface $linker
     */
    public function __construct(ContextInterface $context, LinkerInterface $linker)
    {
        $this->context = $context;
        $this->linker = $linker;
    }

    /**
     * @param NodeInterface $node
     * @return void
     */
    public function enter(NodeInterface $node): void
    {
        if ($node instanceof TypeDefinitionNode && $node->name instanceof TypeName) {
            $map = fn(Identifier $name): string => $name->value;

            $this->genericArguments = \array_map($map, $node->name->arguments);
        }
    }

    /**
     * @param NodeInterface $node
     * @return mixed|void|null
     */
    public function leave(NodeInterface $node)
    {
        switch (true) {
            //
            // If current context was closed then we should remove
            // current state of generic arguments.
            //
            case $node instanceof TypeDefinitionNode:
                $this->genericArguments = [];

                break;

            //
            // Load all type dependencies.
            //
            // Note: Skip type loading if type is part of type template parameter.
            //
            case $node instanceof NamedTypeNode:
                if (! \in_array($node->name->value, $this->genericArguments, true)) {
                    $this->assertTypeExists($node);
                }

                break;

            //
            // Load all directive dependencies
            //
            case $node instanceof DirectiveNode:
                $this->assertDirectiveExists($node->name);
                break;
        }
    }

    /**
     * @param NamedTypeNode $name
     * @return void
     */
    private function assertTypeExists(NamedTypeNode $name): void
    {
        if (! $this->context->hasType($name->name->value)) {
            $this->loadOr($name->name->value, function () use ($name): void {
                throw $this->typeNotFound($name);
            });
        }
    }

    /**
     * @param string $type
     * @param \Closure $otherwise
     * @return void
     */
    private function loadOr(string $type, \Closure $otherwise): void
    {
        /** @var Schema $schema */
        $schema = $this->context->getSchema();

        foreach ($this->linker->getAutoloaders() as $autoloader) {
            $result = $autoloader($type);

            switch (true) {
                // When autoload returns NamedTypeInterface
                case $result instanceof NamedTypeInterface:
                    $schema->addType($result);

                    return;

                // When autoload returns DirectiveInterface
                case $result instanceof DirectiveInterface:
                    $schema->addDirective($result);

                    break;
            }
        }

        $otherwise();
    }

    /**
     * @param NamedTypeNode $name
     * @return TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    private function typeNotFound(NamedTypeNode $name): TypeNotFoundException
    {
        $error = \sprintf(self::ERROR_TYPE_NOT_FOUND, $name->name->value);

        return new TypeNotFoundException($error, $name);
    }

    /**
     * @param NamedDirectiveNode $name
     * @return void
     */
    private function assertDirectiveExists(NamedDirectiveNode $name): void
    {
        if (! $this->context->hasDirective($name->name->value)) {
            $this->loadOr('@' . $name->name->value, function () use ($name): void {
                throw $this->directiveNotFound($name);
            });
        }
    }

    /**
     * @param NamedDirectiveNode $name
     * @return TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    private function directiveNotFound(NamedDirectiveNode $name): TypeNotFoundException
    {
        $error = \sprintf(self::ERROR_DIRECTIVE_NOT_FOUND, $name->name->value);

        return new TypeNotFoundException($error, $name);
    }
}
