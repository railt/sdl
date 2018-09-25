<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Type;

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
     * @var string[]|array[]
     */
    public const INHERITANCE_TREE = [
        self::INTERFACE => [
            self::OBJECT => [
                self::INPUT_OBJECT
            ],
        ],
        self::UNION => [

        ],
        self::SCALAR => [
            self::ENUM
        ]
    ];

    /**
     * @var string
     */
    public const ROOT_TYPE = self::ANY;

    /**
     * Returns a generic constant type name.
     *
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface;

    /**
     * Returns true if the type is the same as the current type.
     *
     * @param TypeInterface $type
     * @return bool
     */
    public function is(TypeInterface $type): bool;

    /**
     * Returns true if the selection type is the
     * one of child of the current type.
     *
     * @param TypeInterface $type
     * @return bool
     */
    public function instanceOf(TypeInterface $type): bool;
}
