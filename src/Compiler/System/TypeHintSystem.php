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
use Railt\Reflection\Contracts\Definition\Behaviour\ProvidesTypeIndication;
use Railt\Reflection\Contracts\Definition\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Definition\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Definition\Dependent\InputFieldDefinition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Definition\Behaviour\HasTypeIndication;
use Railt\SDL\Ast\ProvidesTypeHint;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class TypeHintSystem
 */
class TypeHintSystem extends System
{
    /**
     * @var int
     */
    private const IS_RENDERABLE = 0x01;

    /**
     * @var int
     */
    private const IS_INPUTABLE = 0x02;

    /**
     * @var int[]
     */
    private const TYPE_BEHAVIOURS = [
        FieldDefinition::class      => self::IS_RENDERABLE,
        ArgumentDefinition::class   => self::IS_INPUTABLE,
        InputFieldDefinition::class => self::IS_INPUTABLE,
    ];

    /**
     * @param Definition|HasTypeIndication|TypeDefinition $definition
     * @param RuleInterface $ast
     */
    public function resolve(Definition $definition, RuleInterface $ast): void
    {
        if ($definition instanceof ProvidesTypeIndication && $ast instanceof ProvidesTypeHint) {
            $hint = $ast->getTypeHintNode();

            $definition->withModifiers($hint->getModifiers());

            $this->linker(function () use ($hint, $definition) {
                /** @var TypeDefinition $resolved */
                $resolved = $this->get($hint->getFullName(), $definition);

                $this->verify($definition, $resolved);
            });
        }
    }

    /**
     * @param TypeDefinition $context
     * @param TypeDefinition $hint
     * @throws \Railt\SDL\Exception\CompilerException
     */
    private function verify(TypeDefinition $context, TypeDefinition $hint): void
    {
        foreach (self::TYPE_BEHAVIOURS as $type => $behaviour) {
            if ($context instanceof $type) {
                $error = null;

                switch ($behaviour) {
                    case self::IS_RENDERABLE && ! $hint->isRenderable():
                        $error = '%s "%s" can contain only renderable type (unions, objects, etc), but %s given';
                        break;
                    case self::IS_INPUTABLE && ! $hint->isInputable():
                        $error = '%s "%s" can contain only inputable type (inputs, scalars, etc), but %s given';
                        break;
                }

                if ($error) {
                    $error = \sprintf($error, $context::getType(), $context->getName(), $hint);
                    throw (new TypeConflictException($error))->in($context);
                }
            }
        }
    }
}
