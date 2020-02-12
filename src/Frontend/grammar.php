<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @noinspection ALL
 */

declare(strict_types=1);

use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Contracts\Grammar\RuleInterface;
use Railt\SDL\Frontend\Ast;
use Railt\TypeSystem\Value;

return [

    /**
     * -------------------------------------------------------------------------
     *  Initial State
     * -------------------------------------------------------------------------
     *
     * The initial state (initial rule identifier) of the parser.
     *
     */
    'initial' => 0,
    
    /**
     * -------------------------------------------------------------------------
     *  Lexer Tokens
     * -------------------------------------------------------------------------
     *
     * A GraphQL document is comprised of several kinds of indivisible
     * lexical tokens defined here in a lexical grammar by patterns
     * of source Unicode characters.
     *
     * Tokens are later used as terminal symbols in a GraphQL Document
     * syntactic grammars.
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-Source-Text.Lexical-Tokens
     * @var string[]
     *
     */
    'lexemes' => [
        'T_AND' => '&',
        'T_OR' => '\\|',
        'T_PARENTHESIS_OPEN' => '\\(',
        'T_PARENTHESIS_CLOSE' => '\\)',
        'T_BRACKET_OPEN' => '\\[',
        'T_BRACKET_CLOSE' => '\\]',
        'T_BRACE_OPEN' => '{',
        'T_BRACE_CLOSE' => '}',
        'T_ANGLE_OPEN' => '<',
        'T_ANGLE_CLOSE' => '>',
        'T_NON_NULL' => '!',
        'T_EQUAL' => '=',
        'T_DIRECTIVE_AT' => '@',
        'T_COLON' => ':',
        'T_COMMA' => ',',
        'T_FLOAT_EXP' => '\\-?(?:0|[1-9][0-9]*)(?:[eE][\\+\\-]?[0-9]+)',
        'T_FLOAT' => '\\-?(?:0|[1-9][0-9]*)(?:\\.[0-9]+)(?:[eE][\\+\\-]?[0-9]+)?',
        'T_INT' => '\\-?(?:0|[1-9][0-9]*)',
        'T_TRUE' => '(?<=\\b)true\\b',
        'T_FALSE' => '(?<=\\b)false\\b',
        'T_NULL' => '(?<=\\b)null\\b',
        'T_BLOCK_STRING' => '"""((?:\\\\"|(?!""").)*)"""',
        'T_STRING' => '"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"',
        'T_TYPE' => '(?<=\\b)type\\b',
        'T_ENUM' => '(?<=\\b)enum\\b',
        'T_UNION' => '(?<=\\b)union\\b',
        'T_INTERFACE' => '(?<=\\b)interface\\b',
        'T_SCHEMA' => '(?<=\\b)schema\\b',
        'T_SCALAR' => '(?<=\\b)scalar\\b',
        'T_DIRECTIVE' => '(?<=\\b)directive\\b',
        'T_INPUT' => '(?<=\\b)input\\b',
        'T_QUERY' => '(?<=\\b)query\\b',
        'T_MUTATION' => '(?<=\\b)mutation\\b',
        'T_ON' => '(?<=\\b)on\\b',
        'T_SUBSCRIPTION' => '(?<=\\b)subscription\\b',
        'T_EXTEND' => '(?<=\\b)extend\\b',
        'T_EXTENDS' => '(?<=\\b)extends\\b',
        'T_IN' => '(?<=\\b)in\\b',
        'T_OUT' => '(?<=\\b)out\\b',
        'T_PUBLIC' => '(?<=\\b)public\\b',
        'T_PRIVATE' => '(?<=\\b)private\\b',
        'T_IMPLEMENTS' => '(?<=\\b)implements\\b',
        'T_REPEATABLE' => '(?<=\\b)repeatable\\b',
        'T_VARIABLE' => '\\$([a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*)',
        'T_NAME' => '[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*',
        'T_COMMENT' => '#[^\\n]*',
        'T_BOM' => '\\x{FEFF}',
        'T_HTAB' => '\\x09+',
        'T_WHITESPACE' => '\\x20+',
        'T_LF' => '\\x0A+',
        'T_CR' => '\\x0D+',
        'T_INVISIBLE_WHITESPACES' => '(?:\\x{000B}|\\x{000C}|\\x{0085}|\\x{00A0}|\\x{1680}|[\\x{2000}-\\x{200A}]|\\x{2028}|\\x{2029}|\\x{202F}|\\x{205F}|\\x{3000})+',
        'T_INVISIBLE' => '(?:\\x{180E}|\\x{200B}|\\x{200C}|\\x{200D}|\\x{2060})+',
    ],
     
    /**
     * -------------------------------------------------------------------------
     *  Lexer Ignored Tokens
     * -------------------------------------------------------------------------
     *
     * Before and after every lexical token may be any amount of ignored tokens
     * including WhiteSpace and Comment. No ignored regions of a source document
     * are significant, however otherwise ignored source characters may appear
     * within a lexical token in a significant way, for example a StringValue
     * may contain white space characters and commas.
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-Source-Text.Ignored-Tokens
     * @var string[]
     *
     */
    'skips' => [
        'T_COMMENT',
        'T_BOM',
        'T_HTAB',
        'T_WHITESPACE',
        'T_LF',
        'T_CR',
        'T_INVISIBLE_WHITESPACES',
        'T_INVISIBLE',
    ],
    
    /**
     * -------------------------------------------------------------------------
     *  Parser Grammar
     * -------------------------------------------------------------------------
     *
     * Array of transition rules for the parser.
     *
     */
    'grammar' => [
        1 => new \Phplrt\Grammar\Alternation([
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
            22,
            23,
            24,
            25,
            26,
            27,
            28,
            29,
        ]),
        2 => new \Phplrt\Grammar\Concatenation([
            1,
        ]),
        3 => new \Phplrt\Grammar\Concatenation([
            33,
            30,
            34,
            35,
        ]),
        4 => new \Phplrt\Grammar\Optional(3),
        5 => new \Phplrt\Grammar\Concatenation([
            1,
            4,
        ]),
        6 => new \Phplrt\Grammar\Lexeme('T_TRUE', true),
        7 => new \Phplrt\Grammar\Lexeme('T_FALSE', true),
        8 => new \Phplrt\Grammar\Lexeme('T_NULL', true),
        9 => new \Phplrt\Grammar\Lexeme('T_TYPE', true),
        10 => new \Phplrt\Grammar\Lexeme('T_ENUM', true),
        11 => new \Phplrt\Grammar\Lexeme('T_UNION', true),
        12 => new \Phplrt\Grammar\Lexeme('T_INTERFACE', true),
        13 => new \Phplrt\Grammar\Lexeme('T_SCHEMA', true),
        14 => new \Phplrt\Grammar\Lexeme('T_SCALAR', true),
        15 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE', true),
        16 => new \Phplrt\Grammar\Lexeme('T_INPUT', true),
        17 => new \Phplrt\Grammar\Lexeme('T_QUERY', true),
        18 => new \Phplrt\Grammar\Lexeme('T_MUTATION', true),
        19 => new \Phplrt\Grammar\Lexeme('T_ON', true),
        20 => new \Phplrt\Grammar\Lexeme('T_SUBSCRIPTION', true),
        21 => new \Phplrt\Grammar\Lexeme('T_EXTEND', true),
        22 => new \Phplrt\Grammar\Lexeme('T_EXTENDS', true),
        23 => new \Phplrt\Grammar\Lexeme('T_IN', true),
        24 => new \Phplrt\Grammar\Lexeme('T_OUT', true),
        25 => new \Phplrt\Grammar\Lexeme('T_PUBLIC', true),
        26 => new \Phplrt\Grammar\Lexeme('T_PRIVATE', true),
        27 => new \Phplrt\Grammar\Lexeme('T_IMPLEMENTS', true),
        28 => new \Phplrt\Grammar\Lexeme('T_REPEATABLE', true),
        29 => new \Phplrt\Grammar\Lexeme('T_NAME', true),
        30 => new \Phplrt\Grammar\Concatenation([
            36,
        ]),
        31 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        32 => new \Phplrt\Grammar\Concatenation([
            31,
            30,
        ]),
        33 => new \Phplrt\Grammar\Lexeme('T_ANGLE_OPEN', false),
        34 => new \Phplrt\Grammar\Repetition(32, 0, INF),
        35 => new \Phplrt\Grammar\Lexeme('T_ANGLE_CLOSE', false),
        36 => new \Phplrt\Grammar\Concatenation([
            39,
            1,
        ]),
        37 => new \Phplrt\Grammar\Lexeme('T_IN', false),
        38 => new \Phplrt\Grammar\Lexeme('T_OUT', false),
        39 => new \Phplrt\Grammar\Alternation([
            37,
            38,
        ]),
        40 => new \Phplrt\Grammar\Lexeme('T_IMPLEMENTS', false),
        41 => new \Phplrt\Grammar\Lexeme('T_EXTENDS', false),
        42 => new \Phplrt\Grammar\Concatenation([
            1,
            55,
        ]),
        43 => new \Phplrt\Grammar\Alternation([
            40,
            41,
        ]),
        44 => new \Phplrt\Grammar\Concatenation([
            1,
            43,
            42,
        ]),
        45 => new \Phplrt\Grammar\Alternation([
            89,
            90,
        ]),
        46 => new \Phplrt\Grammar\Optional(45),
        47 => new \Phplrt\Grammar\Concatenation([
            52,
            53,
        ]),
        48 => new \Phplrt\Grammar\Concatenation([
            50,
            49,
            51,
        ]),
        49 => new \Phplrt\Grammar\Alternation([
            47,
            48,
            42,
        ]),
        50 => new \Phplrt\Grammar\Lexeme('T_BRACKET_OPEN', false),
        51 => new \Phplrt\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        52 => new \Phplrt\Grammar\Alternation([
            48,
            42,
        ]),
        53 => new \Phplrt\Grammar\Lexeme('T_NON_NULL', false),
        54 => new \Phplrt\Grammar\Concatenation([
            59,
            42,
            60,
            61,
        ]),
        55 => new \Phplrt\Grammar\Optional(54),
        56 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        57 => new \Phplrt\Grammar\Optional(56),
        58 => new \Phplrt\Grammar\Concatenation([
            57,
            42,
        ]),
        59 => new \Phplrt\Grammar\Lexeme('T_ANGLE_OPEN', false),
        60 => new \Phplrt\Grammar\Repetition(58, 0, INF),
        61 => new \Phplrt\Grammar\Lexeme('T_ANGLE_CLOSE', false),
        62 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        63 => new \Phplrt\Grammar\Concatenation([
            62,
            1,
        ]),
        64 => new \Phplrt\Grammar\Lexeme('T_FALSE', true),
        65 => new \Phplrt\Grammar\Lexeme('T_TRUE', true),
        66 => new \Phplrt\Grammar\Alternation([
            64,
            65,
        ]),
        67 => new \Phplrt\Grammar\Concatenation([
            1,
        ]),
        68 => new \Phplrt\Grammar\Lexeme('T_FLOAT', true),
        69 => new \Phplrt\Grammar\Lexeme('T_FLOAT_EXP', true),
        70 => new \Phplrt\Grammar\Alternation([
            68,
            69,
        ]),
        71 => new \Phplrt\Grammar\Lexeme('T_INT', true),
        72 => new \Phplrt\Grammar\Alternation([
            91,
            92,
            79,
            88,
        ]),
        73 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        74 => new \Phplrt\Grammar\Optional(73),
        75 => new \Phplrt\Grammar\Concatenation([
            72,
            74,
        ]),
        76 => new \Phplrt\Grammar\Lexeme('T_BRACKET_OPEN', false),
        77 => new \Phplrt\Grammar\Repetition(75, 0, INF),
        78 => new \Phplrt\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        79 => new \Phplrt\Grammar\Concatenation([
            76,
            77,
            78,
        ]),
        80 => new \Phplrt\Grammar\Lexeme('T_NULL', true),
        81 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        82 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        83 => new \Phplrt\Grammar\Optional(81),
        84 => new \Phplrt\Grammar\Concatenation([
            1,
            82,
            72,
            83,
        ]),
        85 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        86 => new \Phplrt\Grammar\Repetition(84, 0, INF),
        87 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        88 => new \Phplrt\Grammar\Concatenation([
            85,
            86,
            87,
        ]),
        89 => new \Phplrt\Grammar\Lexeme('T_BLOCK_STRING', true),
        90 => new \Phplrt\Grammar\Lexeme('T_STRING', true),
        91 => new \Phplrt\Grammar\Lexeme('T_VARIABLE', true),
        92 => new \Phplrt\Grammar\Alternation([
            71,
            70,
            45,
            66,
            80,
            67,
        ]),
        93 => new \Phplrt\Grammar\Concatenation([
            117,
            119,
        ]),
        94 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        95 => new \Phplrt\Grammar\Concatenation([
            94,
            93,
        ]),
        96 => new \Phplrt\Grammar\Concatenation([
            188,
            190,
        ]),
        97 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        98 => new \Phplrt\Grammar\Concatenation([
            97,
            96,
        ]),
        99 => new \Phplrt\Grammar\Concatenation([
            197,
            199,
        ]),
        100 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        101 => new \Phplrt\Grammar\Concatenation([
            100,
            99,
        ]),
        102 => new \Phplrt\Grammar\Concatenation([
            207,
            209,
        ]),
        103 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        104 => new \Phplrt\Grammar\Concatenation([
            103,
            102,
        ]),
        105 => new \Phplrt\Grammar\Concatenation([
            225,
            227,
        ]),
        106 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        107 => new \Phplrt\Grammar\Concatenation([
            106,
            105,
        ]),
        108 => new \Phplrt\Grammar\Concatenation([
            235,
        ]),
        109 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        110 => new \Phplrt\Grammar\Concatenation([
            109,
            108,
        ]),
        111 => new \Phplrt\Grammar\Concatenation([
            239,
            241,
        ]),
        112 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        113 => new \Phplrt\Grammar\Concatenation([
            112,
            111,
        ]),
        114 => new \Phplrt\Grammar\Alternation([
            110,
            107,
            104,
            113,
            98,
            101,
        ]),
        115 => new \Phplrt\Grammar\Alternation([
            95,
            114,
        ]),
        116 => new \Phplrt\Grammar\Concatenation([
            46,
            93,
        ]),
        117 => new \Phplrt\Grammar\Concatenation([
            121,
            122,
        ]),
        118 => new \Phplrt\Grammar\Concatenation([
            127,
            128,
            129,
        ]),
        119 => new \Phplrt\Grammar\Optional(118),
        120 => new \Phplrt\Grammar\Concatenation([
            63,
            255,
        ]),
        121 => new \Phplrt\Grammar\Lexeme('T_SCHEMA', false),
        122 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        123 => new \Phplrt\Grammar\Concatenation([
            130,
            131,
            42,
        ]),
        124 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        125 => new \Phplrt\Grammar\Optional(124),
        126 => new \Phplrt\Grammar\Concatenation([
            123,
            125,
        ]),
        127 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        128 => new \Phplrt\Grammar\Repetition(126, 0, INF),
        129 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        130 => new \Phplrt\Grammar\Alternation([
            132,
            133,
            134,
        ]),
        131 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        132 => new \Phplrt\Grammar\Lexeme('T_QUERY', true),
        133 => new \Phplrt\Grammar\Lexeme('T_MUTATION', true),
        134 => new \Phplrt\Grammar\Lexeme('T_SUBSCRIPTION', true),
        135 => new \Phplrt\Grammar\Concatenation([
            140,
            141,
            1,
            142,
            143,
        ]),
        136 => new \Phplrt\Grammar\Concatenation([
            149,
            148,
        ]),
        137 => new \Phplrt\Grammar\Concatenation([
            46,
            135,
            136,
        ]),
        138 => new \Phplrt\Grammar\Concatenation([
            145,
            146,
            147,
        ]),
        139 => new \Phplrt\Grammar\Lexeme('T_REPEATABLE', true),
        140 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE', false),
        141 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        142 => new \Phplrt\Grammar\Optional(138),
        143 => new \Phplrt\Grammar\Optional(139),
        144 => new \Phplrt\Grammar\Concatenation([
            46,
            155,
            158,
            159,
            160,
        ]),
        145 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        146 => new \Phplrt\Grammar\Repetition(144, 0, INF),
        147 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        148 => new \Phplrt\Grammar\Concatenation([
            153,
            1,
            154,
        ]),
        149 => new \Phplrt\Grammar\Lexeme('T_ON', false),
        150 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        151 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        152 => new \Phplrt\Grammar\Concatenation([
            151,
            1,
        ]),
        153 => new \Phplrt\Grammar\Optional(150),
        154 => new \Phplrt\Grammar\Repetition(152, 0, INF),
        155 => new \Phplrt\Grammar\Concatenation([
            1,
            161,
            49,
        ]),
        156 => new \Phplrt\Grammar\Concatenation([
            162,
            72,
        ]),
        157 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        158 => new \Phplrt\Grammar\Optional(156),
        159 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        160 => new \Phplrt\Grammar\Optional(157),
        161 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        162 => new \Phplrt\Grammar\Lexeme('T_EQUAL', false),
        163 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        164 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        165 => new \Phplrt\Grammar\Optional(163),
        166 => new \Phplrt\Grammar\Concatenation([
            46,
            1,
            164,
            165,
        ]),
        167 => new \Phplrt\Grammar\Concatenation([
            46,
            1,
            171,
            172,
            49,
            173,
            174,
        ]),
        168 => new \Phplrt\Grammar\Repetition(167, 1, INF),
        169 => new \Phplrt\Grammar\Concatenation([
            175,
            176,
            177,
        ]),
        170 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        171 => new \Phplrt\Grammar\Optional(169),
        172 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        173 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        174 => new \Phplrt\Grammar\Optional(170),
        175 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        176 => new \Phplrt\Grammar\Repetition(144, 0, INF),
        177 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        178 => new \Phplrt\Grammar\Concatenation([
            1,
            185,
            49,
        ]),
        179 => new \Phplrt\Grammar\Concatenation([
            186,
            72,
        ]),
        180 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        181 => new \Phplrt\Grammar\Optional(179),
        182 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        183 => new \Phplrt\Grammar\Optional(180),
        184 => new \Phplrt\Grammar\Concatenation([
            46,
            178,
            181,
            182,
            183,
        ]),
        185 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        186 => new \Phplrt\Grammar\Lexeme('T_EQUAL', false),
        187 => new \Phplrt\Grammar\Concatenation([
            46,
            96,
        ]),
        188 => new \Phplrt\Grammar\Concatenation([
            191,
            2,
            192,
        ]),
        189 => new \Phplrt\Grammar\Concatenation([
            193,
            194,
            195,
        ]),
        190 => new \Phplrt\Grammar\Optional(189),
        191 => new \Phplrt\Grammar\Lexeme('T_ENUM', false),
        192 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        193 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        194 => new \Phplrt\Grammar\Repetition(166, 0, INF),
        195 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        196 => new \Phplrt\Grammar\Concatenation([
            46,
            99,
        ]),
        197 => new \Phplrt\Grammar\Concatenation([
            200,
            5,
            201,
        ]),
        198 => new \Phplrt\Grammar\Concatenation([
            202,
            203,
            204,
        ]),
        199 => new \Phplrt\Grammar\Optional(198),
        200 => new \Phplrt\Grammar\Lexeme('T_INPUT', false),
        201 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        202 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        203 => new \Phplrt\Grammar\Repetition(184, 0, INF),
        204 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        205 => new \Phplrt\Grammar\Optional(46),
        206 => new \Phplrt\Grammar\Concatenation([
            205,
            102,
        ]),
        207 => new \Phplrt\Grammar\Concatenation([
            211,
            5,
            212,
            213,
        ]),
        208 => new \Phplrt\Grammar\Concatenation([
            214,
            215,
            216,
        ]),
        209 => new \Phplrt\Grammar\Optional(208),
        210 => new \Phplrt\Grammar\Concatenation([
            219,
            220,
            42,
            221,
        ]),
        211 => new \Phplrt\Grammar\Lexeme('T_INTERFACE', false),
        212 => new \Phplrt\Grammar\Optional(210),
        213 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        214 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        215 => new \Phplrt\Grammar\Optional(168),
        216 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        217 => new \Phplrt\Grammar\Alternation([
            222,
            223,
        ]),
        218 => new \Phplrt\Grammar\Concatenation([
            217,
            42,
        ]),
        219 => new \Phplrt\Grammar\Lexeme('T_IMPLEMENTS', false),
        220 => new \Phplrt\Grammar\Optional(217),
        221 => new \Phplrt\Grammar\Repetition(218, 0, INF),
        222 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        223 => new \Phplrt\Grammar\Lexeme('T_AND', false),
        224 => new \Phplrt\Grammar\Concatenation([
            46,
            105,
        ]),
        225 => new \Phplrt\Grammar\Concatenation([
            228,
            5,
            229,
            230,
        ]),
        226 => new \Phplrt\Grammar\Concatenation([
            231,
            232,
            233,
        ]),
        227 => new \Phplrt\Grammar\Optional(226),
        228 => new \Phplrt\Grammar\Lexeme('T_TYPE', false),
        229 => new \Phplrt\Grammar\Optional(210),
        230 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        231 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        232 => new \Phplrt\Grammar\Optional(168),
        233 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        234 => new \Phplrt\Grammar\Concatenation([
            46,
            108,
        ]),
        235 => new \Phplrt\Grammar\Concatenation([
            236,
            2,
            237,
        ]),
        236 => new \Phplrt\Grammar\Lexeme('T_SCALAR', false),
        237 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        238 => new \Phplrt\Grammar\Concatenation([
            46,
            111,
        ]),
        239 => new \Phplrt\Grammar\Concatenation([
            242,
            2,
            243,
        ]),
        240 => new \Phplrt\Grammar\Concatenation([
            245,
            246,
        ]),
        241 => new \Phplrt\Grammar\Optional(240),
        242 => new \Phplrt\Grammar\Lexeme('T_UNION', false),
        243 => new \Phplrt\Grammar\Repetition(120, 0, INF),
        244 => new \Phplrt\Grammar\Concatenation([
            250,
            42,
            251,
        ]),
        245 => new \Phplrt\Grammar\Lexeme('T_EQUAL', false),
        246 => new \Phplrt\Grammar\Optional(244),
        247 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        248 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        249 => new \Phplrt\Grammar\Concatenation([
            248,
            42,
        ]),
        250 => new \Phplrt\Grammar\Optional(247),
        251 => new \Phplrt\Grammar\Repetition(249, 0, INF),
        252 => new \Phplrt\Grammar\Alternation([
            234,
            224,
            206,
            238,
            187,
            196,
        ]),
        253 => new \Phplrt\Grammar\Alternation([
            116,
            137,
            252,
        ]),
        254 => new \Phplrt\Grammar\Concatenation([
            260,
            261,
            262,
        ]),
        255 => new \Phplrt\Grammar\Optional(254),
        257 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        258 => new \Phplrt\Grammar\Optional(257),
        259 => new \Phplrt\Grammar\Concatenation([
            256,
            258,
        ]),
        260 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        261 => new \Phplrt\Grammar\Repetition(259, 0, INF),
        262 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        263 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        256 => new \Phplrt\Grammar\Concatenation([
            1,
            263,
            72,
        ]),
        0 => new \Phplrt\Grammar\Repetition(264, 0, INF),
        264 => new \Phplrt\Grammar\Alternation([
            253,
            115,
        ])
    ],
    
    /**
     * -------------------------------------------------------------------------
     *  Parser Reducers
     * -------------------------------------------------------------------------
     *
     * Array of abstract syntax tree reducers.
     *
     */
    'reducers' => [
        2 => static function ($children) {
            return Ast\TypeName::create($children);
        },
        5 => static function ($children) {
            return Ast\TypeName::create($children);
        },
        1 => static function ($children) {
            return Ast\Identifier::create($children);
        },
        46 => static function ($children) {
            return Ast\Description::create($children ?: null);
        },
        48 => static function ($children) {
            return Ast\Type\ListTypeNode::create($children);
        },
        47 => static function ($children) {
            return Ast\Type\NonNullTypeNode::create($children);
        },
        42 => static function ($children) {
            return Ast\Type\NamedTypeNode::create($children);
        },
        63 => static function ($children) {
            return Ast\Type\NamedDirectiveNode::create($children);
        },
        66 => static function ($children) {
            return Value\BooleanValue::parse($children->getName() === 'T_TRUE');
        },
        67 => static function ($children) {
            return Value\EnumValue::parse($children[0]->value);
        },
        70 => static function ($children) {
            return Value\FloatValue::parse($children->getValue());
        },
        71 => static function ($children) {
            return Value\IntValue::parse($children->getValue());
        },
        79 => static function ($children) {
            return Value\ListValue::parse($children);
        },
        80 => static function ($children) {
            return Value\NullValue::parse(null);
        },
        88 => static function ($children) {
            $result = [];

            for ($i = 0, $count = \count((array)$children); $i < $count; $i += 2) {
                $result[$children[$i]->value] = $children[$i + 1];
            }

            return Value\InputObjectValue::parse($result);
        },
        89 => static function ($children) {
            return Value\StringValue::parse(\substr($children->getValue(), 3, -3));
        },
        90 => static function ($children) {
            return Value\StringValue::parse(\substr($children->getValue(), 1, -1));
        },
        91 => static function ($children) {
            return Ast\Value\VariableValueNode::parse($children[0]->getValue());
        },
        95 => static function ($children) {
            return Ast\Extension\SchemaExtensionNode::create($children);
        },
        98 => static function ($children) {
            return Ast\Extension\Type\EnumTypeExtensionNode::create($children);
        },
        101 => static function ($children) {
            return Ast\Extension\Type\InputObjectTypeExtensionNode::create($children);
        },
        104 => static function ($children) {
            return Ast\Extension\Type\InterfaceTypeExtensionNode::create($children);
        },
        107 => static function ($children) {
            return Ast\Extension\Type\ObjectTypeExtensionNode::create($children);
        },
        110 => static function ($children) {
            return Ast\Extension\Type\ScalarTypeExtensionNode::create($children);
        },
        113 => static function ($children) {
            return Ast\Extension\Type\UnionTypeExtensionNode::create($children);
        },
        116 => static function ($children) {
            return Ast\Definition\SchemaDefinitionNode::create($children);
        },
        123 => static function ($children) {
            return Ast\Definition\OperationTypeDefinitionNode::create($children);
        },
        137 => static function ($children) {
            return Ast\Definition\DirectiveDefinitionNode::create($children);
        },
        139 => static function ($children) {
            return Ast\Definition\DirectiveDefinitionIsRepeatableNode::create();
        },
        148 => static function ($children) {
            return Ast\Definition\DirectiveDefinitionLocationNode::create($children);
        },
        144 => static function ($children) {
            return Ast\Definition\ArgumentDefinitionNode::create($children);
        },
        166 => static function ($children) {
            return Ast\Definition\EnumValueDefinitionNode::create($children);
        },
        167 => static function ($children) {
            return Ast\Definition\FieldDefinitionNode::create($children);
        },
        184 => static function ($children) {
            return Ast\Definition\InputFieldDefinitionNode::create($children);
        },
        187 => static function ($children) {
            return Ast\Definition\Type\EnumTypeDefinitionNode::create($children);
        },
        196 => static function ($children) {
            return Ast\Definition\Type\InputObjectTypeDefinitionNode::create($children);
        },
        206 => static function ($children) {
            return Ast\Definition\Type\InterfaceTypeDefinitionNode::create($children);
        },
        210 => static function ($children) {
            return Ast\Definition\Type\ImplementedInterfaceNode::create($children);
        },
        224 => static function ($children) {
            return Ast\Definition\Type\ObjectTypeDefinitionNode::create($children);
        },
        234 => static function ($children) {
            return Ast\Definition\Type\ScalarTypeDefinitionNode::create($children);
        },
        238 => static function ($children) {
            return Ast\Definition\Type\UnionTypeDefinitionNode::create($children);
        },
        244 => static function ($children) {
            return Ast\Definition\Type\UnionMemberNode::create($children);
        },
        120 => static function ($children) {
            return Ast\Executable\DirectiveNode::create($children);
        },
        256 => static function ($children) {
            return Ast\Executable\ArgumentNode::create($children);
        }
    ],

];