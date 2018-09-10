<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

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
    public const DIRECTIVE_LOCATION = 'DirectiveLocation';

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
    public const INPUT_UNION = 'InputUnion';

    /**
     * @var string
     */
    public const SCHEMA = 'Schema';

    /**
     * @var string
     */
    public const SCHEMA_FIELD = 'SchemaField';

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
    public const INPUT_FIELD_DEFINITION = 'InputField';

    /**
     * @var string
     */
    public const DOCUMENT = 'Document';

    /**
     * @var string
     */
    public const ANY = 'Any';

    /**
     * @var string[]
     */
    public const DEPENDENT_TYPES = [
        self::SCHEMA_FIELD,
        self::ENUM_VALUE,
        self::FIELD,
        self::ARGUMENT,
        self::INPUT_FIELD_DEFINITION,
        self::DOCUMENT,
        self::DIRECTIVE_LOCATION
    ];

    /**
     * @var string[]
     */
    public const ROOT_TYPES = [
        self::SCHEMA,
        self::SCALAR,
        self::OBJECT,
        self::INTERFACE,
        self::UNION,
        self::ENUM,
        self::INPUT_OBJECT,
        self::INPUT_UNION,
        self::DIRECTIVE,
        self::ANY,
    ];

    /**
     * @var string[]
     */
    public const ALLOWS_TO_INPUT = [
        self::SCALAR,
        self::ENUM,
        self::ENUM_VALUE,
        self::INPUT_OBJECT,
        self::INPUT_FIELD_DEFINITION,
        self::INPUT_UNION,
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
        self::INPUT_OBJECT,
        self::INPUT_UNION,
        self::ANY,
    ];

    /**
     * @var string
     */
    public const ROOT_TYPE = self::ANY;

    /**
     * @var string[]|array[]
     */
    public const INHERITANCE_TREE = [
        self::INTERFACE => [
            self::OBJECT => [
                self::INPUT_OBJECT
            ],
        ],
        self::UNION => [
            self::INPUT_UNION
        ],
        self::SCALAR => [
            self::ENUM
        ]
    ];

    /**
     * Returns true if the type is dependent on another
     * independent type definition and false instead.
     *
     * @return bool
     */
    public function isDependent(): bool;

    /**
     * Returns true in the event that the type can be represented
     * as a value and passed as an argument to the GraphQL request.
     *
     * @return bool
     */
    public function isInputable(): bool;

    /**
     * Returns true when a type can be rendered as a JSON
     * value or a structure, and they can also be operated
     * on in the GraphQL query.
     *
     * @return bool
     */
    public function isReturnable(): bool;

    /**
     * Returns a generic constant type name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns true if the selection type is the
     * one of child of the current type.
     *
     * @param TypeInterface $type
     * @return bool
     */
    public function instanceOf(TypeInterface $type): bool;

    /**
     * Returns true if the type is the same as the current type.
     *
     * @param string $type
     * @return bool
     */
    public function is(string $type): bool;

    /**
     * Returns the type name available for output.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * @param string $name
     * @return bool
     */
    public static function isValid(string $name): bool;
}
