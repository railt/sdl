<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Io\Readable;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Value\BooleanValue;
use Railt\SDL\Compiler\Value\ConstantValue;
use Railt\SDL\Compiler\Value\InputValue;
use Railt\SDL\Compiler\Value\ListValue;
use Railt\SDL\Compiler\Value\NullValue;
use Railt\SDL\Compiler\Value\NumberValue;
use Railt\SDL\Compiler\Value\StringValue;
use Railt\SDL\Compiler\Value\ValueInterface;
use Railt\SDL\Exception\SemanticException;

/**
 * Class Factory
 */
final class Value
{
    /**
     * @var string[]|ValueInterface[]|array
     */
    private const DEFAULT_VALUES = [
        NullValue::class,
        ListValue::class,
        InputValue::class,
        NumberValue::class,
        StringValue::class,
        BooleanValue::class,
        ConstantValue::class,
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
     * @param NodeInterface|RuleInterface $rule
     * @param Readable $file
     * @return ValueInterface
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    public static function parse(NodeInterface $rule, Readable $file): ValueInterface
    {
        \assert(\in_array($rule->getName(), ['Key', 'Value'], true), $rule->getName());

        /** @var NodeInterface $child */
        $child = $rule->getChild(0);

        foreach (self::$values as $value) {
            if ($value::match($child)) {
                return new $value($child, $file);
            }
        }

        throw (new SemanticException(\sprintf('Unprocessable value of %s', $child->getName())))
            ->throwsIn($file, $child->getOffset());
    }
}
