<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Builder\Value;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\Builder\BaseBuilder;
use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\SymbolTable\Value;
use Railt\SDL\IR\SymbolTable\ValueInterface;
use Railt\SDL\IR\Type;

/**
 * Class StringValueBuilder
 */
class StringValueBuilder extends BaseBuilder
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
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'StringValue';
    }

    /**
     * @param ContextInterface $ctx
     * @param RuleInterface $rule
     * @return mixed|Value
     */
    public function reduce(ContextInterface $ctx, RuleInterface $rule): ValueInterface
    {
        $value = $this->parse($this->getNativeValue($rule));

        return new Value($value, Type::string());
    }

    /**
     * @param string $value
     * @return string
     */
    public function parse(string $value): string
    {
        // Encode slashes to special "pattern" chars
        $value = $this->encodeSlashes($value);

        // Transform utf char \uXXXX -> X
        $value = $this->renderUtfSequences($value);

        // Transform special chars
        $value = $this->renderSpecialCharacters($value);

        // Decode special patterns to source chars (rollback)
        $value = $this->decodeSlashes($value);

        return $value;
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
     * @param string $value
     * @return string
     */
    private function decodeSlashes(string $value): string
    {
        return \str_replace("\0", '\\', $value);
    }

    /**
     * @param RuleInterface $rule
     * @return string
     */
    private function getNativeValue(RuleInterface $rule): string
    {
        return $rule->getChild(0)->getValue(1);
    }

    /**
     * @return string
     */
    private function getStringValue(): string
    {
        /** @var LeafInterface $leaf */
        $leaf = $this->getChild(0);

        return $leaf->getValue(1);
    }
}
