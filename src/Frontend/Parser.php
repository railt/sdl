<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Railt\Lexer\Factory;
use Railt\Lexer\LexerInterface;
use Railt\Parser\Driver\Llk;
use Railt\Parser\Driver\Stateful;
use Railt\Parser\Grammar;
use Railt\Parser\GrammarInterface;
use Railt\Parser\ParserInterface;
use Railt\Parser\Rule\Alternation;
use Railt\Parser\Rule\Concatenation;
use Railt\Parser\Rule\Repetition;
use Railt\Parser\Rule\Terminal;

/**
 * --- DO NOT EDIT THIS FILE ---
 *
 * Class Parser has been auto-generated.
 * Generated at: 02-10-2018 20:54:06
 *
 * --- DO NOT EDIT THIS FILE ---
 */
class Parser extends Stateful
{
    public const T_AND                 = 'T_AND';
    public const T_OR                  = 'T_OR';
    public const T_PARENTHESIS_OPEN    = 'T_PARENTHESIS_OPEN';
    public const T_PARENTHESIS_CLOSE   = 'T_PARENTHESIS_CLOSE';
    public const T_BRACKET_OPEN        = 'T_BRACKET_OPEN';
    public const T_BRACKET_CLOSE       = 'T_BRACKET_CLOSE';
    public const T_BRACE_OPEN          = 'T_BRACE_OPEN';
    public const T_BRACE_CLOSE         = 'T_BRACE_CLOSE';
    public const T_NON_NULL            = 'T_NON_NULL';
    public const T_THREE_DOTS          = 'T_THREE_DOTS';
    public const T_COLON               = 'T_COLON';
    public const T_EQUAL               = 'T_EQUAL';
    public const T_DIRECTIVE_AT        = 'T_DIRECTIVE_AT';
    public const T_ANGLE_OPEN          = 'T_ANGLE_OPEN';
    public const T_ANGLE_CLOSE         = 'T_ANGLE_CLOSE';
    public const T_COMMA               = 'T_COMMA';
    public const T_HEX_NUMBER          = 'T_HEX_NUMBER';
    public const T_BIN_NUMBER          = 'T_BIN_NUMBER';
    public const T_NUMBER              = 'T_NUMBER';
    public const T_TRUE                = 'T_TRUE';
    public const T_FALSE               = 'T_FALSE';
    public const T_NULL                = 'T_NULL';
    public const T_BLOCK_STRING        = 'T_BLOCK_STRING';
    public const T_STRING              = 'T_STRING';
    public const T_DEBUG               = 'T_DEBUG';
    public const T_NAMESPACE           = 'T_NAMESPACE';
    public const T_NAMESPACE_SEPARATOR = 'T_NAMESPACE_SEPARATOR';
    public const T_IMPORT              = 'T_IMPORT';
    public const T_LET                 = 'T_LET';
    public const T_CONST               = 'T_CONST';
    public const T_EXTEND              = 'T_EXTEND';
    public const T_EXTENDS             = 'T_EXTENDS';
    public const T_IMPLEMENTS          = 'T_IMPLEMENTS';
    public const T_ON                  = 'T_ON';
    public const T_FRAGMENT            = 'T_FRAGMENT';
    public const T_TYPE                = 'T_TYPE';
    public const T_ENUM                = 'T_ENUM';
    public const T_UNION               = 'T_UNION';
    public const T_INTERFACE           = 'T_INTERFACE';
    public const T_SCHEMA              = 'T_SCHEMA';
    public const T_SCALAR              = 'T_SCALAR';
    public const T_DIRECTIVE           = 'T_DIRECTIVE';
    public const T_INPUT               = 'T_INPUT';
    public const T_PLUS                = 'T_PLUS';
    public const T_MINUS               = 'T_MINUS';
    public const T_DIV                 = 'T_DIV';
    public const T_MUL                 = 'T_MUL';
    public const T_VARIABLE            = 'T_VARIABLE';
    public const T_NAME                = 'T_NAME';
    public const T_COMMENT             = 'T_COMMENT';
    public const T_HTAB                = 'T_HTAB';
    public const T_LF                  = 'T_LF';
    public const T_CR                  = 'T_CR';
    public const T_WHITESPACE          = 'T_WHITESPACE';
    public const T_UTF32BE_BOM         = 'T_UTF32BE_BOM';
    public const T_UTF32LE_BOM         = 'T_UTF32LE_BOM';
    public const T_UTF16BE_BOM         = 'T_UTF16BE_BOM';
    public const T_UTF16LE_BOM         = 'T_UTF16LE_BOM';
    public const T_UTF8_BOM            = 'T_UTF8_BOM';
    public const T_UTF7_BOM            = 'T_UTF7_BOM';

    /**
     * Lexical tokens list.
     *
     * @var string[]
     */
    protected const LEXER_TOKENS = [
        self::T_AND                 => '&',
        self::T_OR                  => '\\|',
        self::T_PARENTHESIS_OPEN    => '\\(',
        self::T_PARENTHESIS_CLOSE   => '\\)',
        self::T_BRACKET_OPEN        => '\\[',
        self::T_BRACKET_CLOSE       => '\\]',
        self::T_BRACE_OPEN          => '{',
        self::T_BRACE_CLOSE         => '}',
        self::T_NON_NULL            => '!',
        self::T_THREE_DOTS          => '\\.{3}',
        self::T_COLON               => ':',
        self::T_EQUAL               => '=',
        self::T_DIRECTIVE_AT        => '@',
        self::T_ANGLE_OPEN          => '<',
        self::T_ANGLE_CLOSE         => '>',
        self::T_COMMA               => ',',
        self::T_HEX_NUMBER          => '\\-?0x([0-9a-fA-F]+)',
        self::T_BIN_NUMBER          => '\\-?0b([0-1]+)',
        self::T_NUMBER              => '\\-?(?:0|[1-9][0-9]*)(?:\\.[0-9]+)?(?:[eE][\\+\\-]?[0-9]+)?',
        self::T_TRUE                => '(?<=\\b)true\\b',
        self::T_FALSE               => '(?<=\\b)false\\b',
        self::T_NULL                => '(?<=\\b)null\\b',
        self::T_BLOCK_STRING        => '"""((?:\\\\"""|(?!""").)*)"""',
        self::T_STRING              => '"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"',
        self::T_DEBUG               => '(?<=\\b)__dump\\b',
        self::T_NAMESPACE           => '(?<=\\b)namespace\\b',
        self::T_NAMESPACE_SEPARATOR => '/',
        self::T_IMPORT              => '(?<=\\b)import\\b',
        self::T_LET                 => '(?<=\\b)let\\b',
        self::T_CONST               => '(?<=\\b)const\\b',
        self::T_EXTEND              => '(?<=\\b)extend\\b',
        self::T_EXTENDS             => '(?<=\\b)extends\\b',
        self::T_IMPLEMENTS          => '(?<=\\b)implements\\b',
        self::T_ON                  => '(?<=\\b)on\\b',
        self::T_FRAGMENT            => '(?<=\\b)fragment\\b',
        self::T_TYPE                => '(?<=\\b)type\\b',
        self::T_ENUM                => '(?<=\\b)enum\\b',
        self::T_UNION               => '(?<=\\b)union\\b',
        self::T_INTERFACE           => '(?<=\\b)interface\\b',
        self::T_SCHEMA              => '(?<=\\b)schema\\b',
        self::T_SCALAR              => '(?<=\\b)scalar\\b',
        self::T_DIRECTIVE           => '(?<=\\b)directive\\b',
        self::T_INPUT               => '(?<=\\b)input\\b',
        self::T_PLUS                => '\\\\+',
        self::T_MINUS               => '\\\\-',
        self::T_DIV                 => '\\\\/',
        self::T_MUL                 => '\\\\*',
        self::T_VARIABLE            => '\\$([a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*)',
        self::T_NAME                => '[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*',
        self::T_COMMENT             => '#[^\\n]*',
        self::T_HTAB                => '\\x09',
        self::T_LF                  => '\\x0A',
        self::T_CR                  => '\\x0D',
        self::T_WHITESPACE          => '\\x20',
        self::T_UTF32BE_BOM         => '^\\x00\\x00\\xFE\\xFF',
        self::T_UTF32LE_BOM         => '^\\xFE\\xFF\\x00\\x00',
        self::T_UTF16BE_BOM         => '^\\xFE\\xFF',
        self::T_UTF16LE_BOM         => '^\\xFF\\xFE',
        self::T_UTF8_BOM            => '^\\xEF\\xBB\\xBF',
        self::T_UTF7_BOM            => '^\\x2B\\x2F\\x76\\x38\\x2B\\x2F\\x76\\x39\\x2B\\x2F\\x76\\x2B\\x2B\\x2F\\x76\\x2F',
    ];

    /**
     * List of skipped tokens.
     *
     * @var string[]
     */
    protected const LEXER_SKIPPED_TOKENS = [
        'T_COMMENT',
        'T_HTAB',
        'T_LF',
        'T_CR',
        'T_WHITESPACE',
        'T_UTF32BE_BOM',
        'T_UTF32LE_BOM',
        'T_UTF16BE_BOM',
        'T_UTF16LE_BOM',
        'T_UTF8_BOM',
        'T_UTF7_BOM',
    ];

    /**
     * @var int
     */
    protected const LEXER_FLAGS = Factory::LOOKAHEAD;

    /**
     * List of rule delegates.
     *
     * @var string[]
     */
    protected const PARSER_DELEGATES = [
        'NumberValue'   => \Railt\SDL\Frontend\AST\Value\NumberValueNode::class,
        'StringValue'   => \Railt\SDL\Frontend\AST\Value\StringValueNode::class,
        'NullValue'     => \Railt\SDL\Frontend\AST\Value\NullValueNode::class,
        'ConstantValue' => \Railt\SDL\Frontend\AST\Value\ConstantValueNode::class,
    ];

    /**
     * Parser root rule name.
     *
     * @var string
     */
    protected const PARSER_ROOT_RULE = 'Document';

    /**
     * @return ParserInterface
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    protected function boot(): ParserInterface
    {
        return new Llk($this->bootLexer(), $this->bootGrammar());
    }

    /**
     * @return LexerInterface
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    protected function bootLexer(): LexerInterface
    {
        return Factory::create(static::LEXER_TOKENS, static::LEXER_SKIPPED_TOKENS, static::LEXER_FLAGS);
    }

    /**
     * @return GrammarInterface
     */
    protected function bootGrammar(): GrammarInterface
    {
        return new Grammar([
            new Concatenation(0, ['DocumentBody'], null),
            (new Concatenation('Document', ['DocumentHead', 0], 'Document'))->setDefaultId('Document'),
            new Alternation(2, ['Instruction', 'Directive'], null),
            new Repetition('DocumentHead', 0, -1, 2, null),
            new Alternation(4, ['Instruction', 'Extension', 'Definition'], null),
            new Repetition('DocumentBody', 0, -1, 4, null),
            new Concatenation(6, ['StringValue'], null),
            (new Concatenation('Description', [6], 'Description'))->setDefaultId('Description'),
            new Terminal(8, 'T_EXTENDS', false),
            new Concatenation(9, ['TypeInvocation'], null),
            (new Concatenation('TypeDefinitionExtends', [8, 9], 'TypeDefinitionExtends'))->setDefaultId('TypeDefinitionExtends'),
            new Concatenation(11, ['__typeHintList'], 'TypeHint'),
            new Concatenation(12, ['__typeHintSingular'], null),
            new Concatenation(13, [12], 'TypeHint'),
            (new Alternation('TypeHint', [11, 13], null))->setDefaultId('TypeHint'),
            new Concatenation(15, ['__typeHintNullableList'], null),
            new Alternation('__typeHintList', ['__typeHintNonNullList', 15], null),
            new Terminal(17, 'T_BRACKET_OPEN', false),
            new Terminal(18, 'T_BRACKET_CLOSE', false),
            new Concatenation('__typeHintNullableList', [17, '__typeHintSingular', 18], 'List'),
            new Terminal(20, 'T_NON_NULL', false),
            new Concatenation('__typeHintNonNullList', ['__typeHintNullableList', 20], 'NonNull'),
            new Concatenation(22, ['__typeHintNullableSingular'], null),
            new Alternation('__typeHintSingular', ['__typeHintNonNullSingular', 22], null),
            new Concatenation('__typeHintNullableSingular', ['TypeInvocation'], null),
            new Terminal(25, 'T_NON_NULL', false),
            new Concatenation('__typeHintNonNullSingular', ['TypeInvocation', 25], 'NonNull'),
            new Terminal(27, 'T_IMPLEMENTS', false),
            new Repetition(28, 0, 1, '__implementsDelimiter', null),
            new Concatenation(29, ['__implementsDelimiter', 'TypeInvocation'], 'TypeDefinitionImplements'),
            new Repetition(30, 0, -1, 29, null),
            (new Concatenation('TypeDefinitionImplements', [27, 28, 'TypeInvocation', 30], null))->setDefaultId('TypeDefinitionImplements'),
            new Terminal(32, 'T_COMMA', false),
            new Terminal(33, 'T_AND', false),
            new Alternation('__implementsDelimiter', [32, 33], null),
            new Terminal('NameWithoutReserved', 'T_NAME', true),
            new Terminal(36, 'T_TRUE', true),
            new Terminal(37, 'T_FALSE', true),
            new Terminal(38, 'T_NULL', true),
            new Alternation('NameWithReserved', ['NameExceptValues', 36, 37, 38], null),
            new Terminal(40, 'T_NAMESPACE', true),
            new Terminal(41, 'T_IMPORT', true),
            new Terminal(42, 'T_LET', true),
            new Terminal(43, 'T_CONST', true),
            new Terminal(44, 'T_EXTEND', true),
            new Terminal(45, 'T_EXTENDS', true),
            new Terminal(46, 'T_IMPLEMENTS', true),
            new Terminal(47, 'T_ON', true),
            new Terminal(48, 'T_FRAGMENT', true),
            new Terminal(49, 'T_TYPE', true),
            new Terminal(50, 'T_ENUM', true),
            new Terminal(51, 'T_UNION', true),
            new Terminal(52, 'T_INPUT_UNION', true),
            new Terminal(53, 'T_INTERFACE', true),
            new Terminal(54, 'T_SCHEMA', true),
            new Terminal(55, 'T_SCALAR', true),
            new Terminal(56, 'T_DIRECTIVE', true),
            new Terminal(57, 'T_INPUT', true),
            new Alternation('NameExceptValues', ['NameWithoutReserved', 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57], null),
            new Repetition(59, 0, 1, '__typeNameAtRoot', null),
            new Terminal(60, 'T_NAMESPACE_SEPARATOR', false),
            new Concatenation(61, [60, 'NameWithReserved'], 'TypeName'),
            new Repetition(62, 0, -1, 61, null),
            (new Concatenation('TypeName', [59, 'NameWithReserved', 62], null))->setDefaultId('TypeName'),
            new Terminal(64, 'T_NAMESPACE_SEPARATOR', false),
            new Concatenation('__typeNameAtRoot', [64], 'AtRoot'),
            new Terminal(66, 'T_VARIABLE', true),
            (new Concatenation('VariableName', [66], 'VariableName'))->setDefaultId('VariableName'),
            new Concatenation('ConstantName', ['ConstantValue'], null),
            new Terminal(69, 'T_PARENTHESIS_OPEN', false),
            new Repetition(70, 0, 1, '__argumentDefinitions', null),
            new Terminal(71, 'T_PARENTHESIS_CLOSE', false),
            new Concatenation('ArgumentDefinitions', [69, 70, 71], null),
            new Repetition('__argumentDefinitions', 1, -1, 'ArgumentDefinition', null),
            new Repetition(74, 0, 1, 'Description', null),
            new Repetition(75, 0, 1, '__argumentDefinitionDefaultValue', null),
            new Terminal(76, 'T_COMMA', false),
            new Repetition(77, 0, 1, 76, null),
            new Repetition(78, 0, -1, 'Directive', null),
            new Terminal(79, 'T_COMMA', false),
            new Repetition(80, 0, 1, 79, null),
            (new Concatenation('ArgumentDefinition', [74, '__argumentDefinitionBody', 75, 77, 78, 80], 'ArgumentDefinition'))->setDefaultId('ArgumentDefinition'),
            new Terminal(82, 'T_COLON', false),
            new Concatenation(83, ['TypeHint'], null),
            new Concatenation('__argumentDefinitionBody', ['ConstantName', 82, 83], null),
            new Terminal(85, 'T_EQUAL', false),
            new Concatenation('__argumentDefinitionDefaultValue', [85, 'Value'], 'DefaultValue'),
            new Repetition(87, 0, 1, 'Description', null),
            new Concatenation(88, ['DirectiveDefinitionBody'], null),
            (new Concatenation('DirectiveDefinition', [87, 'DirectiveDefinitionHead', 88], 'DirectiveDefinition'))->setDefaultId('DirectiveDefinition'),
            new Terminal(90, 'T_DIRECTIVE', false),
            new Terminal(91, 'T_DIRECTIVE_AT', false),
            new Repetition(92, 0, 1, 'ArgumentDefinitions', null),
            new Concatenation('DirectiveDefinitionHead', [90, 91, 'TypeDefinition', 92], null),
            new Terminal(94, 'T_ON', false),
            new Concatenation(95, ['DirectiveLocations'], null),
            new Concatenation('DirectiveDefinitionBody', [94, 95], null),
            new Terminal(97, 'T_OR', false),
            new Repetition(98, 0, 1, 97, null),
            new Terminal(99, 'T_OR', false),
            new Concatenation(100, [99, 'DirectiveLocation'], 'DirectiveLocations'),
            new Repetition(101, 0, -1, 100, null),
            (new Concatenation('DirectiveLocations', [98, 'DirectiveLocation', 101], null))->setDefaultId('DirectiveLocations'),
            new Concatenation(103, ['ConstantName'], null),
            (new Concatenation('DirectiveLocation', [103], 'DirectiveLocation'))->setDefaultId('DirectiveLocation'),
            new Repetition(105, 0, 1, 'Description', null),
            new Repetition(106, 0, 1, 'EnumDefinitionBody', null),
            (new Concatenation('EnumDefinition', [105, 'EnumDefinitionHead', 106], 'EnumDefinition'))->setDefaultId('EnumDefinition'),
            new Repetition(108, 0, 1, 'Description', null),
            new Terminal(109, 'T_EXTEND', false),
            new Concatenation(110, ['EnumDefinition'], null),
            (new Concatenation('EnumExtension', [108, 109, 110], 'EnumExtension'))->setDefaultId('EnumExtension'),
            new Terminal(112, 'T_ENUM', false),
            new Repetition(113, 0, -1, 'Directive', null),
            new Concatenation('EnumDefinitionHead', [112, 'TypeDefinition', 113], null),
            new Terminal(115, 'T_BRACE_OPEN', false),
            new Repetition(116, 0, -1, 'EnumValueDefinition', null),
            new Terminal(117, 'T_BRACE_CLOSE', false),
            new Concatenation('EnumDefinitionBody', [115, 116, 117], null),
            new Repetition(119, 0, 1, 'Description', null),
            new Repetition(120, 0, 1, '__enumDefinitionValue', null),
            new Terminal(121, 'T_COMMA', false),
            new Repetition(122, 0, 1, 121, null),
            new Repetition(123, 0, -1, 'Directive', null),
            new Terminal(124, 'T_COMMA', false),
            new Repetition(125, 0, 1, 124, null),
            (new Concatenation('EnumValueDefinition', [119, 'ConstantName', 120, 122, 123, 125], 'EnumValueDefinition'))->setDefaultId('EnumValueDefinition'),
            new Terminal(127, 'T_COLON', false),
            new Terminal(128, 'T_EQUAL', false),
            new Concatenation(129, ['Value'], null),
            new Concatenation('__enumDefinitionValue', [127, 'TypeHint', 128, 129], null),
            new Repetition(131, 0, 1, 'Description', null),
            new Repetition(132, 0, 1, 'ArgumentDefinitions', null),
            new Terminal(133, 'T_COLON', false),
            new Terminal(134, 'T_COMMA', false),
            new Repetition(135, 0, 1, 134, null),
            new Repetition(136, 0, -1, 'Directive', null),
            new Terminal(137, 'T_COMMA', false),
            new Repetition(138, 0, 1, 137, null),
            (new Concatenation('FieldDefinition', [131, 'ConstantName', 132, 133, 'TypeHint', 135, 136, 138], 'FieldDefinition'))->setDefaultId('FieldDefinition'),
            new Repetition(140, 0, 1, 'Description', null),
            new Repetition(141, 0, 1, 'InputDefinitionBody', null),
            (new Concatenation('InputDefinition', [140, 'InputDefinitionHead', 141], 'InputDefinition'))->setDefaultId('InputDefinition'),
            new Repetition(143, 0, 1, 'Description', null),
            new Terminal(144, 'T_EXTEND', false),
            new Concatenation(145, ['InputDefinition'], null),
            (new Concatenation('InputExtension', [143, 144, 145], 'InputExtension'))->setDefaultId('InputExtension'),
            new Terminal(147, 'T_INPUT', false),
            new Repetition(148, 0, -1, 'Directive', null),
            new Concatenation('InputDefinitionHead', [147, 'TypeDefinition', 148], null),
            new Terminal(150, 'T_BRACE_OPEN', false),
            new Repetition(151, 0, -1, 'InputFieldDefinition', null),
            new Terminal(152, 'T_BRACE_CLOSE', false),
            new Concatenation('InputDefinitionBody', [150, 151, 152], null),
            new Repetition(154, 0, 1, 'Description', null),
            new Repetition(155, 0, 1, '__inputFieldDefinitionDefaultValue', null),
            new Terminal(156, 'T_COMMA', false),
            new Repetition(157, 0, 1, 156, null),
            new Repetition(158, 0, -1, 'Directive', null),
            new Terminal(159, 'T_COMMA', false),
            new Repetition(160, 0, 1, 159, null),
            (new Concatenation('InputFieldDefinition', [154, '__inputFieldDefinitionBody', 155, 157, 158, 160], 'InputFieldDefinition'))->setDefaultId('InputFieldDefinition'),
            new Terminal(162, 'T_COLON', false),
            new Concatenation(163, ['TypeHint'], null),
            new Concatenation('__inputFieldDefinitionBody', ['ConstantName', 162, 163], null),
            new Terminal(165, 'T_EQUAL', false),
            new Concatenation(166, ['Value'], null),
            new Concatenation('__inputFieldDefinitionDefaultValue', [165, 166], null),
            new Repetition(168, 0, 1, 'Description', null),
            new Repetition(169, 0, 1, 'InterfaceDefinitionBody', null),
            (new Concatenation('InterfaceDefinition', [168, 'InterfaceDefinitionHead', 169], 'InterfaceDefinition'))->setDefaultId('InterfaceDefinition'),
            new Repetition(171, 0, 1, 'Description', null),
            new Terminal(172, 'T_EXTEND', false),
            new Concatenation(173, ['InterfaceDefinition'], null),
            (new Concatenation('InterfaceExtension', [171, 172, 173], 'InterfaceExtension'))->setDefaultId('InterfaceExtension'),
            new Terminal(175, 'T_INTERFACE', false),
            new Repetition(176, 0, 1, 'TypeDefinitionImplements', null),
            new Repetition(177, 0, -1, 'Directive', null),
            new Concatenation('InterfaceDefinitionHead', [175, 'TypeDefinition', 176, 177], null),
            new Terminal(179, 'T_BRACE_OPEN', false),
            new Repetition(180, 0, -1, 'FieldDefinition', null),
            new Terminal(181, 'T_BRACE_CLOSE', false),
            new Concatenation('InterfaceDefinitionBody', [179, 180, 181], null),
            new Repetition(183, 0, 1, 'Description', null),
            new Repetition(184, 0, 1, 'ObjectDefinitionBody', null),
            (new Concatenation('ObjectDefinition', [183, 'ObjectDefinitionHead', 184], 'ObjectDefinition'))->setDefaultId('ObjectDefinition'),
            new Repetition(186, 0, 1, 'Description', null),
            new Terminal(187, 'T_EXTEND', false),
            new Concatenation(188, ['ObjectDefinition'], null),
            (new Concatenation('ObjectExtension', [186, 187, 188], 'ObjectExtension'))->setDefaultId('ObjectExtension'),
            new Terminal(190, 'T_TYPE', false),
            new Repetition(191, 0, 1, 'TypeDefinitionImplements', null),
            new Repetition(192, 0, -1, 'Directive', null),
            new Concatenation('ObjectDefinitionHead', [190, 'TypeDefinition', 191, 192], null),
            new Terminal(194, 'T_BRACE_OPEN', false),
            new Repetition(195, 0, -1, 'FieldDefinition', null),
            new Terminal(196, 'T_BRACE_CLOSE', false),
            new Concatenation('ObjectDefinitionBody', [194, 195, 196], null),
            new Repetition(198, 0, 1, 'Description', null),
            new Concatenation(199, ['ScalarDefinitionBody'], null),
            (new Concatenation('ScalarDefinition', [198, 199], 'ScalarDefinition'))->setDefaultId('ScalarDefinition'),
            new Repetition(201, 0, 1, 'Description', null),
            new Terminal(202, 'T_EXTEND', false),
            new Concatenation(203, ['ScalarDefinition'], null),
            (new Concatenation('ScalarExtension', [201, 202, 203], 'ScalarExtension'))->setDefaultId('ScalarExtension'),
            new Terminal(205, 'T_SCALAR', false),
            new Repetition(206, 0, 1, 'TypeDefinitionExtends', null),
            new Repetition(207, 0, -1, 'Directive', null),
            new Concatenation('ScalarDefinitionBody', [205, 'TypeDefinition', 206, 207], null),
            new Repetition(209, 0, 1, 'Description', null),
            new Repetition(210, 0, 1, 'SchemaDefinitionBody', null),
            (new Concatenation('SchemaDefinition', [209, 'SchemaDefinitionHead', 210], 'SchemaDefinition'))->setDefaultId('SchemaDefinition'),
            new Repetition(212, 0, 1, 'Description', null),
            new Terminal(213, 'T_EXTEND', false),
            new Concatenation(214, ['SchemaDefinition'], null),
            (new Concatenation('SchemaExtension', [212, 213, 214], 'SchemaExtension'))->setDefaultId('SchemaExtension'),
            new Terminal(216, 'T_SCHEMA', false),
            new Repetition(217, 0, 1, 'TypeDefinition', null),
            new Repetition(218, 0, -1, 'Directive', null),
            new Concatenation('SchemaDefinitionHead', [216, 217, 218], null),
            new Terminal(220, 'T_BRACE_OPEN', false),
            new Repetition(221, 0, -1, 'SchemaFieldDefinition', null),
            new Terminal(222, 'T_BRACE_CLOSE', false),
            new Concatenation('SchemaDefinitionBody', [220, 221, 222], null),
            new Terminal(224, 'T_COLON', false),
            new Terminal(225, 'T_COMMA', false),
            new Repetition(226, 0, 1, 225, null),
            new Repetition(227, 0, -1, 'Directive', null),
            new Terminal(228, 'T_COMMA', false),
            new Repetition(229, 0, 1, 228, null),
            (new Concatenation('SchemaFieldDefinition', ['ConstantName', 224, 'TypeHint', 226, 227, 229], 'SchemaFieldDefinition'))->setDefaultId('SchemaFieldDefinition'),
            new Repetition(231, 0, 1, 'Description', null),
            new Repetition(232, 0, 1, 'UnionDefinitionBody', null),
            (new Concatenation('UnionDefinition', [231, 'UnionDefinitionHead', 232], 'UnionDefinition'))->setDefaultId('UnionDefinition'),
            new Repetition(234, 0, 1, 'Description', null),
            new Terminal(235, 'T_EXTEND', false),
            new Concatenation(236, ['UnionDefinition'], null),
            (new Concatenation('UnionExtension', [234, 235, 236], 'UnionExtension'))->setDefaultId('UnionExtension'),
            new Terminal(238, 'T_UNION', false),
            new Repetition(239, 0, -1, 'Directive', null),
            new Concatenation('UnionDefinitionHead', [238, 'TypeDefinition', 239], null),
            new Terminal(241, 'T_EQUAL', false),
            new Repetition(242, 0, 1, 'UnionDefinitionTargets', null),
            new Concatenation('UnionDefinitionBody', [241, 242], null),
            new Terminal(244, 'T_OR', false),
            new Repetition(245, 0, 1, 244, null),
            new Terminal(246, 'T_OR', false),
            new Concatenation(247, [246, 'TypeInvocation'], 'UnionDefinitionTargets'),
            new Repetition(248, 0, -1, 247, null),
            (new Concatenation('UnionDefinitionTargets', [245, 'TypeInvocation', 248], null))->setDefaultId('UnionDefinitionTargets'),
            new Concatenation(250, ['UnionDefinition'], null),
            new Alternation('Definition', ['DirectiveDefinition', 'SchemaDefinition', 'EnumDefinition', 'InputDefinition', 'InterfaceDefinition', 'ObjectDefinition', 'ScalarDefinition', 250], null),
            new Concatenation(252, ['UnionExtension'], null),
            new Alternation('Extension', ['EnumExtension', 'InputExtension', 'InterfaceExtension', 'ObjectExtension', 'ScalarExtension', 'SchemaExtension', 252], null),
            new Repetition(254, 0, 1, '__genericDefinitionArguments', null),
            (new Concatenation('TypeDefinition', ['TypeName', 254], 'TypeDefinition'))->setDefaultId('TypeDefinition'),
            new Terminal(256, 'T_ANGLE_OPEN', false),
            new Repetition(257, 0, -1, 'GenericDefinitionArgument', null),
            new Terminal(258, 'T_ANGLE_CLOSE', false),
            new Concatenation('__genericDefinitionArguments', [256, 257, 258], null),
            new Terminal(260, 'T_COLON', false),
            new Repetition(261, 0, 1, 'GenericDefinitionArgumentDefaultValue', null),
            new Terminal(262, 'T_COMMA', false),
            new Repetition(263, 0, 1, 262, null),
            (new Concatenation('GenericDefinitionArgument', ['GenericDefinitionArgumentName', 260, 'GenericDefinitionArgumentValue', 261, 263], 'GenericDefinitionArgument'))->setDefaultId('GenericDefinitionArgument'),
            new Concatenation(265, ['ConstantName'], null),
            (new Concatenation('GenericDefinitionArgumentName', [265], 'GenericDefinitionArgumentName'))->setDefaultId('GenericDefinitionArgumentName'),
            new Concatenation(267, ['TypeName'], null),
            (new Concatenation('GenericDefinitionArgumentValue', [267], 'GenericDefinitionArgumentValue'))->setDefaultId('GenericDefinitionArgumentValue'),
            new Terminal(269, 'T_EQUAL', false),
            new Concatenation(270, ['TypeInvocation'], null),
            (new Concatenation('GenericDefinitionArgumentDefaultValue', [269, 270], 'GenericDefinitionArgumentDefaultValue'))->setDefaultId('GenericDefinitionArgumentDefaultValue'),
            new Terminal(272, 'T_PARENTHESIS_OPEN', false),
            new Repetition(273, 0, -1, 'ArgumentValue', null),
            new Terminal(274, 'T_PARENTHESIS_CLOSE', false),
            new Concatenation('ArgumentValues', [272, 273, 274], null),
            new Terminal(276, 'T_COLON', false),
            new Terminal(277, 'T_COMMA', false),
            new Repetition(278, 0, 1, 277, null),
            (new Concatenation('ArgumentValue', ['ConstantName', 276, 'Value', 278], 'ArgumentValue'))->setDefaultId('ArgumentValue'),
            new Terminal(280, 'T_DIRECTIVE_AT', false),
            new Repetition(281, 0, 1, 'ArgumentValues', null),
            (new Concatenation('Directive', [280, 'TypeInvocation', 281], 'Directive'))->setDefaultId('Directive'),
            new Terminal(283, 'T_FALSE', true),
            new Concatenation(284, [283], 'BooleanValue'),
            new Terminal(285, 'T_TRUE', true),
            new Concatenation(286, [285], 'BooleanValue'),
            (new Alternation('BooleanValue', [284, 286], null))->setDefaultId('BooleanValue'),
            new Terminal(288, 'T_NUMBER', true),
            new Concatenation(289, [288], 'NumberValue'),
            new Terminal(290, 'T_HEX_NUMBER', true),
            new Concatenation(291, [290], 'NumberValue'),
            new Terminal(292, 'T_BIN_NUMBER', true),
            new Concatenation(293, [292], 'NumberValue'),
            (new Alternation('NumberValue', [289, 291, 293], null))->setDefaultId('NumberValue'),
            new Terminal(295, 'T_BLOCK_STRING', true),
            new Concatenation(296, [295], 'StringValue'),
            new Terminal(297, 'T_STRING', true),
            new Concatenation(298, [297], 'StringValue'),
            (new Alternation('StringValue', [296, 298], null))->setDefaultId('StringValue'),
            new Terminal(300, 'T_NULL', false),
            (new Concatenation('NullValue', [300], 'NullValue'))->setDefaultId('NullValue'),
            new Terminal(302, 'T_BRACE_OPEN', false),
            new Repetition(303, 0, -1, 'ArgumentValue', null),
            new Terminal(304, 'T_BRACE_CLOSE', false),
            (new Concatenation('InputValue', [302, 303, 304], 'InputValue'))->setDefaultId('InputValue'),
            new Terminal(306, 'T_BRACKET_OPEN', false),
            new Repetition(307, 0, -1, 'Value', null),
            new Terminal(308, 'T_BRACKET_CLOSE', false),
            (new Concatenation('ListValue', [306, 307, 308], 'ListValue'))->setDefaultId('ListValue'),
            new Concatenation(310, ['NameExceptValues'], null),
            (new Concatenation('ConstantValue', [310], 'ConstantValue'))->setDefaultId('ConstantValue'),
            new Concatenation(312, ['ListValue'], null),
            new Alternation('Value', ['BooleanValue', 'NullValue', 'NumberValue', 'StringValue', 'InputValue', 312], null),
            new Repetition(314, 0, 1, '__genericInvocationArguments', null),
            (new Concatenation('TypeInvocation', ['GenericInvocationName', 314], 'TypeInvocation'))->setDefaultId('TypeInvocation'),
            new Concatenation(316, ['TypeName'], 'GenericInvocationName'),
            new Concatenation(317, ['VariableName'], null),
            new Concatenation(318, [317], 'GenericInvocationName'),
            (new Alternation('GenericInvocationName', [316, 318], null))->setDefaultId('GenericInvocationName'),
            new Terminal(320, 'T_ANGLE_OPEN', false),
            new Repetition(321, 0, -1, 'GenericInvocationArgument', null),
            new Terminal(322, 'T_ANGLE_CLOSE', false),
            new Concatenation('__genericInvocationArguments', [320, 321, 322], null),
            new Terminal(324, 'T_COLON', false),
            new Terminal(325, 'T_COMMA', false),
            new Repetition(326, 0, 1, 325, null),
            new Concatenation('GenericInvocationArgument', ['GenericInvocationArgumentName', 324, 'GenericInvocationArgumentValue', 326], null),
            new Concatenation(328, ['ConstantName'], 'GenericInvocationArgumentName'),
            new Concatenation(329, ['VariableName'], null),
            new Concatenation(330, [329], 'GenericInvocationArgumentName'),
            (new Alternation('GenericInvocationArgumentName', [328, 330], null))->setDefaultId('GenericInvocationArgumentName'),
            new Concatenation(332, ['TypeInvocation'], 'GenericInvocationArgumentValue'),
            new Concatenation(333, ['VariableName'], null),
            new Concatenation(334, [333], 'GenericInvocationArgumentValue'),
            (new Alternation('GenericInvocationArgumentValue', [332, 334], null))->setDefaultId('GenericInvocationArgumentValue'),
            new Terminal(336, 'T_IMPORT', false),
            new Concatenation(337, ['Value'], 'ImportDefinition'),
            new Concatenation(338, ['VariableName'], 'ImportDefinition'),
            new Alternation(339, [337, 338], null),
            (new Concatenation('ImportDefinition', [336, 339], null))->setDefaultId('ImportDefinition'),
            new Terminal(341, 'T_NAMESPACE', false),
            new Concatenation(342, ['TypeName'], null),
            (new Concatenation('NamespaceDefinition', [341, 342], 'NamespaceDefinition'))->setDefaultId('NamespaceDefinition'),
            new Concatenation(344, ['__variableDefinitionBody'], null),
            (new Concatenation('VariableReassigment', [344], 'VariableReassigment'))->setDefaultId('VariableReassigment'),
            new Terminal(346, 'T_LET', false),
            new Concatenation(347, ['__variableDefinitionBody'], null),
            (new Concatenation('VariableDefinition', [346, 347], 'VariableDefinition'))->setDefaultId('VariableDefinition'),
            new Terminal(349, 'T_CONST', false),
            new Concatenation(350, ['__variableDefinitionBody'], null),
            (new Concatenation('ConstantDefinition', [349, 350], 'ConstantDefinition'))->setDefaultId('ConstantDefinition'),
            new Terminal(352, 'T_EQUAL', false),
            new Concatenation(353, ['VariableName', 352], null),
            new Repetition(354, 1, -1, 353, null),
            new Concatenation(355, ['VariableValue'], null),
            new Concatenation('__variableDefinitionBody', [354, 355], null),
            new Concatenation(357, ['VariableName'], 'VariableValue'),
            new Concatenation(358, ['Value'], null),
            new Concatenation(359, [358], 'VariableValue'),
            (new Alternation('VariableValue', [357, 359], null))->setDefaultId('VariableValue'),
            new Concatenation(361, ['VariableReassigment'], null),
            new Alternation('Instruction', ['NamespaceDefinition', 'ImportDefinition', 'ConstantDefinition', 'VariableDefinition', 361], null),
        ], static::PARSER_ROOT_RULE, static::PARSER_DELEGATES);
    }
}
