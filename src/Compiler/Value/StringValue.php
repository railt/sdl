<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Value;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;

/**
 * Class StringValue
 */
class StringValue extends BaseValue
{
    /**
     * @var string
     */
    private const UTF_SEQUENCE_PATTERN = '/(?<!\\\\)\\\\u([0-9a-f]{4})/ui';

    /**
     * @var string
     */
    private const CHAR_SEQUENCE_PATTERN = '/(?<!\\\\)\\\\(b|f|n|r|t)/u';

    /**
     * @return string
     */
    protected static function getAstName(): string
    {
        return 'String';
    }

    /**
     * @param NodeInterface|RuleInterface $rule
     * @return mixed|string
     */
    protected function parse(NodeInterface $rule): string
    {
        $result = $this->unpackStringData($rule->getChild(0));

        // Encode slashes to special "pattern" chars
        $result = $this->encodeSlashes($result);

        // Transform utf char \uXXXX -> X
        $result = $this->renderUtfSequences($result);

        // Transform special chars
        $result = $this->renderSpecialCharacters($result);

        // Decode special patterns to source chars (rollback)
        $result = $this->decodeSlashes($result);

        return $result;
    }

    /**
     * @param string $value
     * @return string
     */
    private function encodeSlashes(string $value): string
    {
        return \str_replace(['\\\\', '\\"'], ["\0", '"'], $value);
    }

    /**
     * @param string $value
     * @return string
     */
    private function decodeSlashes(string $value): string
    {
        return \str_replace("\0", '\\', $value);
    }

    /**
     * @param LeafInterface $ast
     * @return string
     */
    private function unpackStringData(LeafInterface $ast): string
    {
        return $ast->getValue(1) ?? '';
    }

    /**
     * Method for parsing special control characters.
     *
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     * @param string $body
     * @return string
     */
    private function renderSpecialCharacters(string $body): string
    {
        $callee = function (array $matches): string {
            [$char, $code] = [$matches[0], $matches[1]];

            switch ($code) {
                case 'b':
                    return "\u{0008}";
                case 'f':
                    return "\u{000C}";
                case 'n':
                    return "\u{000A}";
                case 'r':
                    return "\u{000D}";
                case 't':
                    return "\u{0009}";
            }

            return $char;
        };

        return @\preg_replace_callback(self::CHAR_SEQUENCE_PATTERN, $callee, $body) ?? $body;
    }

    /**
     * Method for parsing and decode utf-8 character
     * sequences like "\uXXXX" type.
     *
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     * @param string $body
     * @return string
     */
    private function renderUtfSequences(string $body): string
    {
        $callee = function (array $matches): string {
            [$char, $code] = [$matches[0], $matches[1]];

            try {
                return $this->forwardRenderUtfSequences($code);
            } catch (\Error | \ErrorException $error) {
                return $this->fallbackRenderUtfSequences($char);
            }
        };

        return @\preg_replace_callback(self::UTF_SEQUENCE_PATTERN, $callee, $body) ?? $body;
    }

    /**
     * @param string $body
     * @return string
     */
    private function forwardRenderUtfSequences(string $body): string
    {
        return \mb_convert_encoding(\pack('H*', $body), 'UTF-8', 'UCS-2BE');
    }

    /**
     * @param string $body
     * @return string
     */
    private function fallbackRenderUtfSequences(string $body): string
    {
        try {
            if (\function_exists('\\json_decode')) {
                $result = @\json_decode('{"char": "' . $body . '"}')->char;

                if (\json_last_error() === \JSON_ERROR_NONE) {
                    $body = $result;
                }
            }
        } finally {
            return $body;
        }
    }
}
