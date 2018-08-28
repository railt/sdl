<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Io\Readable;
use Railt\Parser\Ast\NodeInterface;
use Railt\SDL\Frontend\IR\OpcodeInterface;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Class Renderer
 */
class Renderer
{
    /**
     * @param mixed $value
     * @return string
     */
    public static function render($value): string
    {
        switch (true) {
            case $value instanceof NodeInterface:
                return self::node($value);

            case $value instanceof OpcodeInterface:
                return self::opcode($value);

            case $value instanceof ValueInterface:
                return self::value($value);

            case $value instanceof Readable:
                return self::file($value);

            case \is_scalar($value):
                return self::scalar($value);

            case \is_iterable($value):
                return self::iterable($value);

            case \is_object($value):
                return self::object($value);
        }

        return \gettype($value);
    }

    /**
     * @param NodeInterface $ast
     * @return string
     */
    private static function node(NodeInterface $ast): string
    {
        return '<' . $ast->getName() . '>';
    }

    /**
     * @param OpcodeInterface $opcode
     * @return string
     */
    private static function opcode(OpcodeInterface $opcode): string
    {
        return '#' . $opcode->getId() . ' ' . $opcode->getName();
    }

    /**
     * @param ValueInterface $value
     * @return string
     */
    private static function value(ValueInterface $value): string
    {
        return (string)$value;
    }

    /**
     * @param mixed $value
     * @return string
     */
    private static function scalar($value): string
    {
        switch (true) {
            case $value === null:
                return 'null';

            case \is_bool($value):
                return $value ? 'true' : 'false';

            case \is_string($value):
                $minified = \preg_replace('/\s+/', ' ', (string)$value);
                return '"' . \addcslashes($minified, '"') . '"';

            default:
                return (string)$value;
        }
    }

    /**
     * @param iterable $values
     * @return string
     */
    private static function iterable(iterable $values): string
    {
        $arguments = [];

        foreach ($values as $value) {
            $arguments[] = self::render($value);
        }

        $name = \is_array($values) ? 'array' : \get_class($values);

        return \sprintf('%s(%s)', $name, \implode(', ', $arguments));
    }

    /**
     * @param mixed $value
     * @return string
     */
    private static function object($value): string
    {
        return \get_class($value) . '#' . \spl_object_hash($value);
    }

    /**
     * @param Readable $readable
     * @return string
     */
    private static function file(Readable $readable): string
    {
        return '(file)' . $readable->getPathname();
    }
}
