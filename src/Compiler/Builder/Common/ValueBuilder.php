<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Common;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\Behaviour\ProvidesTypeIndication as TypeHint;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Definition\EnumDefinition;
use Railt\Reflection\Invocation\InputInvocation;
use Railt\Reflection\Type;
use Railt\SDL\Compiler\Ast\Value\ConstantValueNode;
use Railt\SDL\Compiler\Ast\Value\InputValueNode;
use Railt\SDL\Compiler\Ast\Value\ListValueNode;
use Railt\SDL\Compiler\Ast\Value\NullValueNode;
use Railt\SDL\Compiler\Ast\Value\ValueInterface;
use Railt\SDL\Compiler\Ast\Value\ValueNode;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class ValueBuilder
 */
class ValueBuilder
{
    /**
     * @var ValueTypeResolver
     */
    private $resolver;

    /**
     * @var Definition|TypeHint
     */
    private $type;

    /**
     * @param TypeHint|Definition $type
     */
    public function __construct(TypeHint $type)
    {
        $this->type = $type;
        $this->resolver = new ValueTypeResolver($this->type->getDocument()->getDictionary());
    }

    /**
     * @param ValueInterface $value
     * @return array|mixed
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public function valueOf(ValueInterface $value)
    {
        return $this->getValueOf($value, $this->type);
    }

    /**
     * @param ValueInterface $value
     * @param TypeHint $type
     * @return array|mixed
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function getValueOf(ValueInterface $value, TypeHint $type)
    {
        if ($value instanceof ValueNode) {
            return $this->getValueOf($value->getInnerValue(), $type);
        }

        if ($type->isList()) {
            return \iterator_to_array($this->valueOfList($type, $value));
        }

        return $this->valueOfNonList($type, $value);
    }

    /**
     * @param TypeHint|TypeDefinition $type
     * @param ValueInterface|ListValueNode $value
     * @return \Traversable
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function valueOfList(TypeHint $type, ValueInterface $value): \Traversable
    {
        if ($value instanceof NullValueNode) {
            /**
             * @validation <name: Type! = null>
             */
            if ($type->isNonNull()) {
                $error = 'Non-Null type %s can not accept null value';
                throw (new TypeConflictException(\sprintf($error, $type)))->throwsIn($type->getFile(),
                    $value->getOffset());
            }

            return $value->toPrimitive();
        }

        /**
         * @validation <name: [Type] = Value>
         */
        if (! ($value instanceof ListValueNode)) {
            $error = 'Value of %s should be a List, but %s given';
            throw (new TypeConflictException(\sprintf($error, $type, $value->toString())))->throwsIn($type->getFile(),
                $value->getOffset());
        }

        foreach ($value->getValues() as $leaf) {
            /**
             * @validation <name: [Type!] = [null]>
             */
            if ($leaf instanceof NullValueNode) {
                if ($type->isListOfNonNulls()) {
                    $error = 'List of Non-Nulls %s can not accept null value';
                    throw (new TypeConflictException(\sprintf($error, $type)))->throwsIn($type->getFile(),
                        $leaf->getOffset());
                }

                yield $value->toPrimitive();
            } else {
                yield $this->extractValue($type, $leaf);
            }
        }
    }

    /**
     * @param TypeHint|TypeDefinition $type
     * @param ValueInterface|RuleInterface $value
     * @return mixed
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function extractValue(TypeHint $type, ValueInterface $value)
    {
        $definition = $type->getDefinition();

        if ($definition::typeOf(Type::of(Type::INPUT_OBJECT))) {
            return $this->extractInput($type, $value);
        }

        if ($definition::typeOf(Type::of(Type::ENUM))) {
            return $this->extractEnum($type, $value);
        }

        if ($definition::typeOf(Type::of(Type::SCALAR))) {
            return $this->extractScalar($type, $value);
        }

        return $value->toPrimitive();
    }

    /**
     * @param TypeHint|TypeDefinition $type
     * @param ValueInterface|RuleInterface|InputValueNode $value
     * @return InputInvocation
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function extractInput(TypeHint $type, ValueInterface $value): InputInvocation
    {
        /**
         * @validation <name: InputType = Value>
         */
        if (! ($value instanceof InputValueNode)) {
            $error = 'Value of %s should be a %s, but %s given';
            throw (new TypeConflictException(\sprintf($error, $type, $type->getDefinition(),
                $value->toString())))->throwsIn($type->getFile(), $value->getOffset());
        }

        /** @var Definition\InputDefinition $definition */
        $definition = $type->getDefinition();

        $invocation = new InputInvocation($type->getDocument(), $definition->getName());
        $invocation->withOffset($value->getOffset());

        /**
         * @var LeafInterface $key
         * @var ValueInterface $child
         */
        foreach ($value->getValues() as $key => $child) {
            $name = $key->getValue();

            /**
             * @validation <name: InputType = {nonExistentField: Value}>
             */
            if (! $definition->hasField($name)) {
                $error = 'Input field "%s" does not provided by %s, but %s given';
                throw (new TypeConflictException(\sprintf($error, $name, $type->getDefinition(),
                    $value->toString())))->throwsIn($type->getFile(), $value->getOffset());
            }

            $invocation->withArgument($name, $this->getValueOf($child, $definition->getField($name)));
        }

        return $invocation;
    }

    /**
     * @param TypeHint|TypeDefinition|ConstantValueNode $type
     * @param ValueInterface|RuleInterface $value
     * @return mixed
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function extractEnum(TypeHint $type, ValueInterface $value)
    {
        /**
         * @validation <name: Enum = "NotEnumValue">
         */
        if (! ($value instanceof ConstantValueNode)) {
            $error = 'Value of %s can be one of %s value, but %s given';
            throw (new TypeConflictException(\sprintf($error, $type, $type->getDefinition(),
                $value->toString())))->throwsIn($type->getFile(), $value->getOffset());
        }

        /** @var EnumDefinition $definition */
        $definition = $type->getDefinition();

        $name = $value->toPrimitive();

        /**
         * @validation <name: Enum = NonExistentValue>
         */
        if (! $definition->hasValue($name)) {
            $error = 'Enum %s does not provide value %s';
            throw (new TypeConflictException(\sprintf($error, $type->getDefinition(), $value->toString())))
                ->throwsIn($type->getFile(), $value->getOffset());
        }

        return $definition->getValue($name)->getValue();
    }

    /**
     * @param TypeHint|TypeDefinition $type
     * @param ValueInterface|RuleInterface $value
     * @return bool|float|int|mixed|null|string
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    private function extractScalar(TypeHint $type, ValueInterface $value)
    {
        if ($value instanceof NullValueNode) {
            return null;
        }

        try {
            return $this->resolver->castTo($type->getDefinition(), $value->toPrimitive(), $value->toString());
        } catch (TypeConflictException $e) {
            throw $e->throwsIn($type->getFile(), $value->getOffset());
        }
    }

    /**
     * @param TypeHint|TypeDefinition $type
     * @param ValueInterface $value
     * @return mixed
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    private function valueOfNonList(TypeHint $type, ValueInterface $value)
    {
        /**
         * @validation <name: TypeHint = []>
         */
        if ($value instanceof ListValueNode) {
            $error = 'Value of %s should be a Non-List, but %s given';
            throw (new TypeConflictException(\sprintf($error, $type, $value->toString())))->throwsIn($type->getFile(),
                $value->getOffset());
        }

        /**
         * @validation <name: TypeHint! = null>
         */
        if ($value instanceof NullValueNode) {
            if ($type->isNonNull()) {
                $error = 'Non-Null type %s can not accept null value';
                throw (new TypeConflictException(\sprintf($error, $type)))->throwsIn($type->getFile(),
                    $value->getOffset());
            }

            return $value->toPrimitive();
        }

        return $this->extractValue($type, $value);
    }
}
