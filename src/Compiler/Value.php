<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Value;

use Railt\Parser\Ast\NodeInterface;
use Railt\SDL\Exception\SemanticException;

/**
 * Class Factory
 */
final class Factory
{
    /**
     * @var string[]|ValueInterface[]|array
     */
    private const DEFAULT_VALUES = [
        EnumValue::class,
        NullValue::class,
        ListValue::class,
        NumberValue::class,
        StringValue::class,
        BooleanValue::class,
    ];

    /**
     * @var string[]|ValueInterface[]|array
     */
    private static $values = self::DEFAULT_VALUES;

    /**
     * @param string $value
     */
    public static function share(string $value): void
    {
        self::$values[] = $value;
    }

    /**
     * @param NodeInterface $rule
     * @return ValueInterface
     * @throws SemanticException
     */
    public static function parse(NodeInterface $rule): ValueInterface
    {
        foreach (self::$values as $value) {
            if ($value::match($rule)) {
                return new $value($rule);
            }
        }

        throw new SemanticException(\sprintf('Unprocessable value of %s', $rule->getName()));
    }
}
