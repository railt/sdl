<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type;

/**
 * Interface TypeInterface
 */
interface TypeInterface
{
    /**
     * @var string
     */
    public const SCALAR = 'Scalar';

    /**
     * @var string
     */
    public const OBJECT = 'Object';

    /**
     * @var string
     */
    public const DIRECTIVE = 'Directive';

    /**
     * @var string
     */
    public const INTERFACE = 'Interface';

    /**
     * @var string
     */
    public const UNION = 'Union';

    /**
     * @var string
     */
    public const ENUM = 'Enum';

    /**
     * @var string
     */
    public const INPUT_OBJECT = 'Input';

    /**
     * @var string
     */
    public const SCHEMA = 'Schema';

    /**
     * @var string
     */
    public const ANY = 'Any';

    /**
     * @var string
     */
    public const DOCUMENT = 'Document';

    /**
     * @var string
     */
    public const ENUM_VALUE = 'EnumValue';

    /**
     * @var string
     */
    public const FIELD = 'Field';

    /**
     * @var string
     */
    public const ARGUMENT = 'Argument';

    /**
     * @var string
     */
    public const INPUT_FIELD = 'InputField';

    /**
     * @var string
     */
    public const LIST = 'List';

    /**
     * @var string
     */
    public const NON_NULL = 'NonNull';

    /**
     * @var string
     */
    public const ROOT_TYPE = self::ANY;

    /**
     * @var string
     */
    public const STRING = 'String';

    /**
     * @var string
     */
    public const INT = 'Int';

    /**
     * @var string
     */
    public const BOOLEAN = 'Boolean';

    /**
     * @var string
     */
    public const FLOAT = 'Float';

    /**
     * @var string
     */
    public const ID = 'ID';

    /**
     * @var string
     */
    public const DATE_TIME = 'DateTime';

    /**
     * @var string
     */
    public const NULL = 'Null';

    /**
     * @var string[]
     */
    public const INDEPENDENT_TYPES = [
        self::SCALAR,
        self::OBJECT,
        self::DIRECTIVE,
        self::INTERFACE,
        self::UNION,
        self::ENUM,
        self::INPUT_OBJECT,
        self::SCHEMA,
        self::ANY,
        self::DOCUMENT,
    ];

    /**
     * @var string[]
     */
    public const DEPENDENT_TYPES = [
        self::ENUM_VALUE,
        self::FIELD,
        self::ARGUMENT,
        self::INPUT_FIELD,
    ];

    /**
     * @var string[]
     */
    public const WRAPPING_TYPES = [
        self::NON_NULL,
        self::LIST,
    ];

    /**
     * @var string[]
     */
    public const ALLOWS_TO_INPUT = [
        self::SCALAR,
        self::ENUM,
        self::ENUM_VALUE,
        self::INPUT_OBJECT,
        self::INPUT_FIELD,
        self::NON_NULL,
        self::LIST,
        self::ANY
    ];

    /**
     * @var string[]
     */
    public const ALLOWS_TO_OUTPUT = [
        self::SCALAR,
        self::OBJECT,
        self::INTERFACE,
        self::UNION,
        self::ENUM,
        self::NON_NULL,
        self::LIST,
        self::ANY,
    ];

    /**
     * @var string[]
     */
    public const RUNTIME_TYPES = [
        self::STRING,
        self::INT,
        self::BOOLEAN,
        self::FLOAT,
        self::ID,
        self::DATE_TIME,
        self::NULL,
    ];

    /**
     * Returns a generic constant type name.
     *
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface;

    /**
     * @return TypeInterface
     */
    public function getParent(): TypeInterface;

    /**
     * Returns true if the selection type is the one of
     * child of the current type.
     *
     * @param TypeInterface $type
     * @return bool
     */
    public function typeOf(TypeInterface $type): bool;

    /**
     * Returns true if the type is the same as the current type.
     *
     * @param TypeInterface $type
     * @return bool
     */
    public function is(TypeInterface $type): bool;

    /**
     * Returns true in case if the type is builtin.
     *
     * @return bool
     */
    public function isBuiltin(): bool;

    /**
     * Returns true in case if that the type can be represented
     * as a value and passed as an argument to the GraphQL request.
     *
     * @return bool
     */
    public function isInputable(): bool;

    /**
     * Returns true in case if a type can be rendered as a JSON
     * value or a structure, and they can also be operated
     * on in the GraphQL query.
     *
     * @return bool
     */
    public function isReturnable(): bool;

    /**
     * @return string
     */
    public function getHash(): string;
}
