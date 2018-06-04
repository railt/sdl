<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Compiler\LexerInterface;
use Railt\Compiler\Parser as ParserCompiler;
use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Runtime;
use Railt\Compiler\ParserInterface;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Parser\Compiled;

/**
 * Class Parser
 */
class Parser implements ParserInterface
{
    /**
     * @var string Grammar file path
     */
    public const GRAMMAR_FILE = __DIR__ . '/../../resources/grammar/sdl.pp2';

    /**
     * @var Parser
     */
    private static $instance;

    /**
     * @var ParserInterface
     */
    private $runtime;

    /**
     * Parser constructor.
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function __construct()
    {
        $this->runtime = $this->resolve();
    }

    /**
     * @return ParserInterface
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function resolve(): ParserInterface
    {
        if (\class_exists(Compiled::class)) {
            return new Compiled();
        }

        return ParserCompiler::fromGrammar(File::fromPathname(self::GRAMMAR_FILE));
    }

    /**
     * @return Parser
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public static function new(): Parser
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface
    {
        return $this->runtime->getLexer();
    }

    /**
     * @param Readable $input
     * @return NodeInterface
     */
    public function parse(Readable $input): NodeInterface
    {
        return $this->runtime->parse($input);
    }
}
