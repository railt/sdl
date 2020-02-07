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
        ]),
        2 => new \Phplrt\Grammar\Concatenation([
            1,
        ]),
        3 => new \Phplrt\Grammar\Concatenation([
            29,
            1,
            30,
            31,
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
        17 => new \Phplrt\Grammar\Lexeme('T_EXTEND', true),
        18 => new \Phplrt\Grammar\Lexeme('T_EXTENDS', true),
        19 => new \Phplrt\Grammar\Lexeme('T_IMPLEMENTS', true),
        20 => new \Phplrt\Grammar\Lexeme('T_ON', true),
        21 => new \Phplrt\Grammar\Lexeme('T_REPEATABLE', true),
        22 => new \Phplrt\Grammar\Lexeme('T_QUERY', true),
        23 => new \Phplrt\Grammar\Lexeme('T_MUTATION', true),
        24 => new \Phplrt\Grammar\Lexeme('T_SUBSCRIPTION', true),
        25 => new \Phplrt\Grammar\Lexeme('T_NAME', true),
        26 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        27 => new \Phplrt\Grammar\Optional(26),
        28 => new \Phplrt\Grammar\Concatenation([
            27,
            1,
        ]),
        29 => new \Phplrt\Grammar\Lexeme('T_ANGLE_OPEN', false),
        30 => new \Phplrt\Grammar\Repetition(28, 0, INF),
        31 => new \Phplrt\Grammar\Lexeme('T_ANGLE_CLOSE', false),
        32 => new \Phplrt\Grammar\Alternation([
            77,
            78,
        ]),
        33 => new \Phplrt\Grammar\Optional(32),
        34 => new \Phplrt\Grammar\Concatenation([
            40,
            41,
        ]),
        35 => new \Phplrt\Grammar\Concatenation([
            38,
            37,
            39,
        ]),
        36 => new \Phplrt\Grammar\Concatenation([
            1,
            43,
        ]),
        37 => new \Phplrt\Grammar\Alternation([
            34,
            35,
            36,
        ]),
        38 => new \Phplrt\Grammar\Lexeme('T_BRACKET_OPEN', false),
        39 => new \Phplrt\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        40 => new \Phplrt\Grammar\Alternation([
            35,
            36,
        ]),
        41 => new \Phplrt\Grammar\Lexeme('T_NON_NULL', false),
        42 => new \Phplrt\Grammar\Concatenation([
            47,
            36,
            48,
            49,
        ]),
        43 => new \Phplrt\Grammar\Optional(42),
        44 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        45 => new \Phplrt\Grammar\Optional(44),
        46 => new \Phplrt\Grammar\Concatenation([
            45,
            36,
        ]),
        47 => new \Phplrt\Grammar\Lexeme('T_ANGLE_OPEN', false),
        48 => new \Phplrt\Grammar\Repetition(46, 0, INF),
        49 => new \Phplrt\Grammar\Lexeme('T_ANGLE_CLOSE', false),
        50 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        51 => new \Phplrt\Grammar\Concatenation([
            50,
            1,
        ]),
        52 => new \Phplrt\Grammar\Lexeme('T_FALSE', true),
        53 => new \Phplrt\Grammar\Lexeme('T_TRUE', true),
        54 => new \Phplrt\Grammar\Alternation([
            52,
            53,
        ]),
        55 => new \Phplrt\Grammar\Concatenation([
            1,
        ]),
        56 => new \Phplrt\Grammar\Lexeme('T_FLOAT', true),
        57 => new \Phplrt\Grammar\Lexeme('T_FLOAT_EXP', true),
        58 => new \Phplrt\Grammar\Alternation([
            56,
            57,
        ]),
        59 => new \Phplrt\Grammar\Lexeme('T_INT', true),
        60 => new \Phplrt\Grammar\Alternation([
            79,
            80,
            67,
            76,
        ]),
        61 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        62 => new \Phplrt\Grammar\Optional(61),
        63 => new \Phplrt\Grammar\Concatenation([
            60,
            62,
        ]),
        64 => new \Phplrt\Grammar\Lexeme('T_BRACKET_OPEN', false),
        65 => new \Phplrt\Grammar\Repetition(63, 0, INF),
        66 => new \Phplrt\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        67 => new \Phplrt\Grammar\Concatenation([
            64,
            65,
            66,
        ]),
        68 => new \Phplrt\Grammar\Lexeme('T_NULL', true),
        69 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        70 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        71 => new \Phplrt\Grammar\Optional(69),
        72 => new \Phplrt\Grammar\Concatenation([
            1,
            70,
            60,
            71,
        ]),
        73 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        74 => new \Phplrt\Grammar\Repetition(72, 0, INF),
        75 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        76 => new \Phplrt\Grammar\Concatenation([
            73,
            74,
            75,
        ]),
        77 => new \Phplrt\Grammar\Lexeme('T_BLOCK_STRING', true),
        78 => new \Phplrt\Grammar\Lexeme('T_STRING', true),
        79 => new \Phplrt\Grammar\Lexeme('T_VARIABLE', true),
        80 => new \Phplrt\Grammar\Alternation([
            59,
            58,
            32,
            54,
            68,
            55,
        ]),
        81 => new \Phplrt\Grammar\Concatenation([
            105,
            107,
        ]),
        82 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        83 => new \Phplrt\Grammar\Concatenation([
            82,
            81,
        ]),
        84 => new \Phplrt\Grammar\Concatenation([
            176,
            178,
        ]),
        85 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        86 => new \Phplrt\Grammar\Concatenation([
            85,
            84,
        ]),
        87 => new \Phplrt\Grammar\Concatenation([
            185,
            187,
        ]),
        88 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        89 => new \Phplrt\Grammar\Concatenation([
            88,
            87,
        ]),
        90 => new \Phplrt\Grammar\Concatenation([
            195,
            197,
        ]),
        91 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        92 => new \Phplrt\Grammar\Concatenation([
            91,
            90,
        ]),
        93 => new \Phplrt\Grammar\Concatenation([
            213,
            215,
        ]),
        94 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        95 => new \Phplrt\Grammar\Concatenation([
            94,
            93,
        ]),
        96 => new \Phplrt\Grammar\Concatenation([
            223,
        ]),
        97 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        98 => new \Phplrt\Grammar\Concatenation([
            97,
            96,
        ]),
        99 => new \Phplrt\Grammar\Concatenation([
            227,
            229,
        ]),
        100 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        101 => new \Phplrt\Grammar\Concatenation([
            100,
            99,
        ]),
        102 => new \Phplrt\Grammar\Alternation([
            98,
            95,
            92,
            101,
            86,
            89,
        ]),
        103 => new \Phplrt\Grammar\Alternation([
            83,
            102,
        ]),
        104 => new \Phplrt\Grammar\Concatenation([
            33,
            81,
        ]),
        105 => new \Phplrt\Grammar\Concatenation([
            109,
            110,
        ]),
        106 => new \Phplrt\Grammar\Concatenation([
            115,
            116,
            117,
        ]),
        107 => new \Phplrt\Grammar\Optional(106),
        108 => new \Phplrt\Grammar\Concatenation([
            51,
            243,
        ]),
        109 => new \Phplrt\Grammar\Lexeme('T_SCHEMA', false),
        110 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        111 => new \Phplrt\Grammar\Concatenation([
            118,
            119,
            36,
        ]),
        112 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        113 => new \Phplrt\Grammar\Optional(112),
        114 => new \Phplrt\Grammar\Concatenation([
            111,
            113,
        ]),
        115 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        116 => new \Phplrt\Grammar\Repetition(114, 0, INF),
        117 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        118 => new \Phplrt\Grammar\Alternation([
            120,
            121,
            122,
        ]),
        119 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        120 => new \Phplrt\Grammar\Lexeme('T_QUERY', true),
        121 => new \Phplrt\Grammar\Lexeme('T_MUTATION', true),
        122 => new \Phplrt\Grammar\Lexeme('T_SUBSCRIPTION', true),
        123 => new \Phplrt\Grammar\Concatenation([
            128,
            129,
            1,
            130,
            131,
        ]),
        124 => new \Phplrt\Grammar\Concatenation([
            137,
            136,
        ]),
        125 => new \Phplrt\Grammar\Concatenation([
            33,
            123,
            124,
        ]),
        126 => new \Phplrt\Grammar\Concatenation([
            133,
            134,
            135,
        ]),
        127 => new \Phplrt\Grammar\Lexeme('T_REPEATABLE', true),
        128 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE', false),
        129 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        130 => new \Phplrt\Grammar\Optional(126),
        131 => new \Phplrt\Grammar\Optional(127),
        132 => new \Phplrt\Grammar\Concatenation([
            33,
            143,
            146,
            147,
            148,
        ]),
        133 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        134 => new \Phplrt\Grammar\Repetition(132, 0, INF),
        135 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        136 => new \Phplrt\Grammar\Concatenation([
            141,
            1,
            142,
        ]),
        137 => new \Phplrt\Grammar\Lexeme('T_ON', false),
        138 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        139 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        140 => new \Phplrt\Grammar\Concatenation([
            139,
            1,
        ]),
        141 => new \Phplrt\Grammar\Optional(138),
        142 => new \Phplrt\Grammar\Repetition(140, 0, INF),
        143 => new \Phplrt\Grammar\Concatenation([
            1,
            149,
            37,
        ]),
        144 => new \Phplrt\Grammar\Concatenation([
            150,
            60,
        ]),
        145 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        146 => new \Phplrt\Grammar\Optional(144),
        147 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        148 => new \Phplrt\Grammar\Optional(145),
        149 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        150 => new \Phplrt\Grammar\Lexeme('T_EQUAL', false),
        151 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        152 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        153 => new \Phplrt\Grammar\Optional(151),
        154 => new \Phplrt\Grammar\Concatenation([
            33,
            1,
            152,
            153,
        ]),
        155 => new \Phplrt\Grammar\Concatenation([
            33,
            1,
            159,
            160,
            37,
            161,
            162,
        ]),
        156 => new \Phplrt\Grammar\Repetition(155, 1, INF),
        157 => new \Phplrt\Grammar\Concatenation([
            163,
            164,
            165,
        ]),
        158 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        159 => new \Phplrt\Grammar\Optional(157),
        160 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        161 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        162 => new \Phplrt\Grammar\Optional(158),
        163 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        164 => new \Phplrt\Grammar\Repetition(132, 0, INF),
        165 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        166 => new \Phplrt\Grammar\Concatenation([
            1,
            173,
            37,
        ]),
        167 => new \Phplrt\Grammar\Concatenation([
            174,
            60,
        ]),
        168 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        169 => new \Phplrt\Grammar\Optional(167),
        170 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        171 => new \Phplrt\Grammar\Optional(168),
        172 => new \Phplrt\Grammar\Concatenation([
            33,
            166,
            169,
            170,
            171,
        ]),
        173 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        174 => new \Phplrt\Grammar\Lexeme('T_EQUAL', false),
        175 => new \Phplrt\Grammar\Concatenation([
            33,
            84,
        ]),
        176 => new \Phplrt\Grammar\Concatenation([
            179,
            2,
            180,
        ]),
        177 => new \Phplrt\Grammar\Concatenation([
            181,
            182,
            183,
        ]),
        178 => new \Phplrt\Grammar\Optional(177),
        179 => new \Phplrt\Grammar\Lexeme('T_ENUM', false),
        180 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        181 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        182 => new \Phplrt\Grammar\Repetition(154, 0, INF),
        183 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        184 => new \Phplrt\Grammar\Concatenation([
            33,
            87,
        ]),
        185 => new \Phplrt\Grammar\Concatenation([
            188,
            5,
            189,
        ]),
        186 => new \Phplrt\Grammar\Concatenation([
            190,
            191,
            192,
        ]),
        187 => new \Phplrt\Grammar\Optional(186),
        188 => new \Phplrt\Grammar\Lexeme('T_INPUT', false),
        189 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        190 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        191 => new \Phplrt\Grammar\Repetition(172, 0, INF),
        192 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        193 => new \Phplrt\Grammar\Optional(33),
        194 => new \Phplrt\Grammar\Concatenation([
            193,
            90,
        ]),
        195 => new \Phplrt\Grammar\Concatenation([
            199,
            5,
            200,
            201,
        ]),
        196 => new \Phplrt\Grammar\Concatenation([
            202,
            203,
            204,
        ]),
        197 => new \Phplrt\Grammar\Optional(196),
        198 => new \Phplrt\Grammar\Concatenation([
            207,
            208,
            36,
            209,
        ]),
        199 => new \Phplrt\Grammar\Lexeme('T_INTERFACE', false),
        200 => new \Phplrt\Grammar\Optional(198),
        201 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        202 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        203 => new \Phplrt\Grammar\Optional(156),
        204 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        205 => new \Phplrt\Grammar\Alternation([
            210,
            211,
        ]),
        206 => new \Phplrt\Grammar\Concatenation([
            205,
            36,
        ]),
        207 => new \Phplrt\Grammar\Lexeme('T_IMPLEMENTS', false),
        208 => new \Phplrt\Grammar\Optional(205),
        209 => new \Phplrt\Grammar\Repetition(206, 0, INF),
        210 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        211 => new \Phplrt\Grammar\Lexeme('T_AND', false),
        212 => new \Phplrt\Grammar\Concatenation([
            33,
            93,
        ]),
        213 => new \Phplrt\Grammar\Concatenation([
            216,
            5,
            217,
            218,
        ]),
        214 => new \Phplrt\Grammar\Concatenation([
            219,
            220,
            221,
        ]),
        215 => new \Phplrt\Grammar\Optional(214),
        216 => new \Phplrt\Grammar\Lexeme('T_TYPE', false),
        217 => new \Phplrt\Grammar\Optional(198),
        218 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        219 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        220 => new \Phplrt\Grammar\Optional(156),
        221 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        222 => new \Phplrt\Grammar\Concatenation([
            33,
            96,
        ]),
        223 => new \Phplrt\Grammar\Concatenation([
            224,
            2,
            225,
        ]),
        224 => new \Phplrt\Grammar\Lexeme('T_SCALAR', false),
        225 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        226 => new \Phplrt\Grammar\Concatenation([
            33,
            99,
        ]),
        227 => new \Phplrt\Grammar\Concatenation([
            230,
            2,
            231,
        ]),
        228 => new \Phplrt\Grammar\Concatenation([
            233,
            234,
        ]),
        229 => new \Phplrt\Grammar\Optional(228),
        230 => new \Phplrt\Grammar\Lexeme('T_UNION', false),
        231 => new \Phplrt\Grammar\Repetition(108, 0, INF),
        232 => new \Phplrt\Grammar\Concatenation([
            238,
            36,
            239,
        ]),
        233 => new \Phplrt\Grammar\Lexeme('T_EQUAL', false),
        234 => new \Phplrt\Grammar\Optional(232),
        235 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        236 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        237 => new \Phplrt\Grammar\Concatenation([
            236,
            36,
        ]),
        238 => new \Phplrt\Grammar\Optional(235),
        239 => new \Phplrt\Grammar\Repetition(237, 0, INF),
        240 => new \Phplrt\Grammar\Alternation([
            222,
            212,
            194,
            226,
            175,
            184,
        ]),
        241 => new \Phplrt\Grammar\Alternation([
            104,
            125,
            240,
        ]),
        242 => new \Phplrt\Grammar\Concatenation([
            248,
            249,
            250,
        ]),
        243 => new \Phplrt\Grammar\Optional(242),
        245 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        246 => new \Phplrt\Grammar\Optional(245),
        247 => new \Phplrt\Grammar\Concatenation([
            244,
            246,
        ]),
        248 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        249 => new \Phplrt\Grammar\Repetition(247, 0, INF),
        250 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        251 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        244 => new \Phplrt\Grammar\Concatenation([
            1,
            251,
            60,
        ]),
        0 => new \Phplrt\Grammar\Repetition(252, 0, INF),
        252 => new \Phplrt\Grammar\Alternation([
            241,
            103,
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
        33 => static function ($children) {
            return Ast\Description::create($children ?: null);
        },
        35 => static function ($children) {
            return Ast\Type\ListTypeNode::create($children);
        },
        34 => static function ($children) {
            return Ast\Type\NonNullTypeNode::create($children);
        },
        36 => static function ($children) {
            return Ast\Type\NamedTypeNode::create($children);
        },
        51 => static function ($children) {
            return Ast\Type\NamedDirectiveNode::create($children);
        },
        54 => static function ($children) {
            return Value\BooleanValue::parse($children->getName() === 'T_TRUE');
        },
        55 => static function ($children) {
            return Value\EnumValue::parse($children[0]->value);
        },
        58 => static function ($children) {
            return Value\FloatValue::parse($children->getValue());
        },
        59 => static function ($children) {
            return Value\IntValue::parse($children->getValue());
        },
        67 => static function ($children) {
            return Value\ListValue::parse($children);
        },
        68 => static function ($children) {
            return Value\NullValue::parse(null);
        },
        76 => static function ($children) {
            $result = [];

            for ($i = 0, $count = \count((array)$children); $i < $count; $i += 2) {
                $result[$children[$i]->value] = $children[$i + 1];
            }

            return Value\InputObjectValue::parse($result);
        },
        77 => static function ($children) {
            return Value\StringValue::parse(\substr($children->getValue(), 3, -3));
        },
        78 => static function ($children) {
            return Value\StringValue::parse(\substr($children->getValue(), 1, -1));
        },
        79 => static function ($children) {
            return Ast\Value\VariableValueNode::parse($children[0]->getValue());
        },
        83 => static function ($children) {
            return Ast\Extension\SchemaExtensionNode::create($children);
        },
        86 => static function ($children) {
            return Ast\Extension\Type\EnumTypeExtensionNode::create($children);
        },
        89 => static function ($children) {
            return Ast\Extension\Type\InputObjectTypeExtensionNode::create($children);
        },
        92 => static function ($children) {
            return Ast\Extension\Type\InterfaceTypeExtensionNode::create($children);
        },
        95 => static function ($children) {
            return Ast\Extension\Type\ObjectTypeExtensionNode::create($children);
        },
        98 => static function ($children) {
            return Ast\Extension\Type\ScalarTypeExtensionNode::create($children);
        },
        101 => static function ($children) {
            return Ast\Extension\Type\UnionTypeExtensionNode::create($children);
        },
        104 => static function ($children) {
            return Ast\Definition\SchemaDefinitionNode::create($children);
        },
        111 => static function ($children) {
            return Ast\Definition\OperationTypeDefinitionNode::create($children);
        },
        125 => static function ($children) {
            return Ast\Definition\DirectiveDefinitionNode::create($children);
        },
        127 => static function ($children) {
            return Ast\Definition\DirectiveDefinitionIsRepeatableNode::create();
        },
        136 => static function ($children) {
            return Ast\Definition\DirectiveDefinitionLocationNode::create($children);
        },
        132 => static function ($children) {
            return Ast\Definition\ArgumentDefinitionNode::create($children);
        },
        154 => static function ($children) {
            return Ast\Definition\EnumValueDefinitionNode::create($children);
        },
        155 => static function ($children) {
            return Ast\Definition\FieldDefinitionNode::create($children);
        },
        172 => static function ($children) {
            return Ast\Definition\InputFieldDefinitionNode::create($children);
        },
        175 => static function ($children) {
            return Ast\Definition\Type\EnumTypeDefinitionNode::create($children);
        },
        184 => static function ($children) {
            return Ast\Definition\Type\InputObjectTypeDefinitionNode::create($children);
        },
        194 => static function ($children) {
            return Ast\Definition\Type\InterfaceTypeDefinitionNode::create($children);
        },
        198 => static function ($children) {
            return Ast\Definition\Type\ImplementedInterfaceNode::create($children);
        },
        212 => static function ($children) {
            return Ast\Definition\Type\ObjectTypeDefinitionNode::create($children);
        },
        222 => static function ($children) {
            return Ast\Definition\Type\ScalarTypeDefinitionNode::create($children);
        },
        226 => static function ($children) {
            return Ast\Definition\Type\UnionTypeDefinitionNode::create($children);
        },
        232 => static function ($children) {
            return Ast\Definition\Type\UnionMemberNode::create($children);
        },
        108 => static function ($children) {
            return Ast\Executable\DirectiveNode::create($children);
        },
        244 => static function ($children) {
            return Ast\Executable\ArgumentNode::create($children);
        }
    ],

];