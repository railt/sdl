<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Reflection\Contracts\Definition\Behaviour\ProvidesTypeIndication;

/**
 * Class Renderer
 */
class Renderer
{
    /**
     * @param string $name
     * @param int $modifiers
     * @return string
     */
    public static function typeIndication(string $name, int $modifiers = 0): string
    {
        $result = $name;

        if (self::is($modifiers, ProvidesTypeIndication::IS_NOT_NULL)) {
            $result .= '!';
        }

        if (self::is($modifiers, ProvidesTypeIndication::IS_LIST)) {
            $result = '[' . $result . ']';
        }

        if (self::is($modifiers, ProvidesTypeIndication::IS_LIST_OF_NOT_NULL)) {
            $result .= '!';
        }

        return $result;
    }

    /**
     * @param int $mask
     * @param int $val
     * @return bool
     */
    private static function is(int $mask, int $val): bool
    {
        return ($mask & $val) === $val;
    }
}

