<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

/**
 * Class Matcher
 */
class Matcher extends Parser
{
    /**
     * @param string $lexeme
     * @return string
     */
    public static function pattern(string $lexeme): string
    {
        return \sprintf('/^%s$/', self::LEXER_TOKENS[$lexeme]);
    }

    /**
     * @param string $lexeme
     * @param string $value
     * @return bool
     */
    public static function match(string $lexeme, string $value): bool
    {
        return (bool)\preg_match(self::pattern($lexeme), $value);
    }
}
