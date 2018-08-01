<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Definition;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Document as DocumentInterface;
use Railt\Reflection\Definition\Dependent\EnumValueDefinition;
use Railt\Reflection\Definition\EnumDefinition;
use Railt\Reflection\Document;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class EnumDelegate
 */
class EnumDelegate extends DefinitionDelegate
{
    /**
     * @param DocumentInterface|Document $document
     * @return Definition
     */
    protected function bootDefinition(DocumentInterface $document): Definition
    {
        return new EnumDefinition($document, $this->getTypeName());
    }

    /**
     * @param Definition|EnumDefinition $definition
     */
    protected function before(Definition $definition): void
    {
        $this->bootValues($definition);
    }

    /**
     * @param EnumDefinition $enum
     */
    private function bootValues(EnumDefinition $enum): void
    {
        foreach ($this->getEnumValues($enum) as $value) {
            $this->transaction($value, function (EnumValueDefinition $value) use ($enum) {
                $this->verifyDuplication($enum, $value);
                $enum->withValue($value);
            });
        }
    }

    /**
     * @param EnumDefinition $enum
     * @return iterable
     */
    private function getEnumValues(EnumDefinition $enum): iterable
    {
        /** @var RuleInterface $ast */
        foreach ($this->first('EnumValues', 1) as $ast) {
            $enumValue = $this->createEnumValue($enum, $ast->first('T_NAME', 1));

            $this->transaction($enumValue, function (EnumValueDefinition $def) use ($ast) {
                $this->withDescription($def, $ast);

                /** @var RuleInterface $defValue */
                if ($defValue = $ast->first('Value', 1)) {
                    $def->withValue($this->value($defValue)->toScalar());
                }
            });

            yield $enumValue;
        }
    }

    /**
     * @param EnumDefinition $enum
     * @param LeafInterface|NodeInterface $name
     * @return EnumValueDefinition
     */
    private function createEnumValue(EnumDefinition $enum, LeafInterface $name): EnumValueDefinition
    {
        $def = new EnumValueDefinition($enum, $name->getValue());
        $def->withOffset($name->getOffset());

        return $def;
    }

    /**
     * @param EnumDefinition $enum
     * @param EnumValueDefinition $value
     * @throws \Railt\SDL\Exception\CompilerException
     */
    private function verifyDuplication(EnumDefinition $enum, EnumValueDefinition $value): void
    {
        if ($enum->hasValue($value->getName())) {
            $error = 'Could not define %s, because %s already exists';
            $error = \sprintf($error, $value, $enum->getValue($value->getName()));

            throw (new TypeConflictException($error))->using($this->getCallStack())->in($value);
        }
    }
}
