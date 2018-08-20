<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\Behaviour\ProvidesInterfaces;
use Railt\Reflection\Contracts\Definition\InterfaceDefinition;
use Railt\Reflection\Contracts\Definition\ObjectDefinition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Definition\Behaviour\HasInterfaces;
use Railt\SDL\Ast\Common\TypeNameNode;
use Railt\SDL\Ast\ProvidesInterfaceNodes;
use Railt\SDL\Exception\CompilerException;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class ImplementationSystem
 */
class ImplementationSystem extends System
{
    /**
     * @param Definition|ProvidesInterfaces|HasInterfaces $parent
     * @param RuleInterface|ProvidesInterfaceNodes $ast
     */
    public function resolve(Definition $parent, RuleInterface $ast): void
    {
        if ($parent instanceof ProvidesInterfaces && $ast instanceof ProvidesInterfaceNodes) {
            $this->inference(function() use ($ast, $parent) {
                foreach ($ast->getInterfaceNodes() as $node) {
                    $this->implement($parent, $node);
                }
            });

            if ($parent instanceof ObjectDefinition) {
                $this->inference(function () use ($parent) {
                    foreach ($parent->getInterfaces() as $interface) {
                        $this->verifyFieldsInheritance($parent, $interface);
                    }
                });
            }
        }
    }

    /**
     * @param ProvidesInterfaces|HasInterfaces|TypeDefinition $parent
     * @param TypeNameNode $ast
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    private function implement(ProvidesInterfaces $parent, TypeNameNode $ast): void
    {
        /** @var InterfaceDefinition $interface */
        $interface = $this->get($ast->getFullName(), $parent);

        try {
            $this->verifyIsInterface($parent, $interface);
            $this->verifyInterfaceDuplication($parent, $interface);

            $parent->withInterface($interface);

            $this->verifySelfReferenceImplementation($parent, $interface);
        } catch (CompilerException $e) {
            throw $e->throwsIn($parent->getFile(), $ast->getOffset());
        }
    }

    /**
     * @param ObjectDefinition $object
     * @param InterfaceDefinition $interface
     */
    private function verifyFieldsInheritance(ObjectDefinition $object, InterfaceDefinition $interface): void
    {
        // TODO
    }

    /**
     * @param Definition $parent
     * @param TypeDefinition $definition
     * @throws TypeConflictException
     */
    private function verifyIsInterface(Definition $parent, TypeDefinition $definition): void
    {
        if (! $definition instanceof InterfaceDefinition) {
            $error = '%s can implement only interfaces, but %s given';
            throw new TypeConflictException(\sprintf($error, $parent, $definition));
        }
    }

    /**
     * @param ProvidesInterfaces $parent
     * @param TypeDefinition $interface
     * @throws TypeConflictException
     */
    private function verifyInterfaceDuplication(ProvidesInterfaces $parent, TypeDefinition $interface): void
    {
        if ($parent->isImplements($interface)) {
            $error = \sprintf('Can not implement the same interface %s twice', $interface);

            throw new TypeConflictException($error);
        }
    }

    /**
     * @param ProvidesInterfaces $parent
     * @param TypeDefinition $interface
     * @throws TypeConflictException
     */
    private function verifySelfReferenceImplementation(ProvidesInterfaces $parent, TypeDefinition $interface): void
    {
        if ($parent->isImplements($parent)) {
            $error = 'Can not implement the interface %s by %s, ' .
                'because it contains a reference to the already implemented type %s';
            $error = \sprintf($error, $interface, $parent, $parent);

            throw new TypeConflictException($error);
        }
    }
}
