<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Parser;

use Railt\Compiler\Parser;
use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Runtime;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class Parser
 */
class Factory
{
    /**
     * @var string Grammar file path
     */
    public const GRAMMAR_FILE = __DIR__ . '/../../resources/grammar/sdl.pp2';

    /**
     * @var Runtime
     */
    private $runtime;

    /**
     * Factory constructor.
     * @param Runtime $runtime
     */
    public function __construct(Runtime $runtime)
    {
        $this->runtime = $runtime;
    }

    /**
     * @return Factory
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public static function create(): self
    {
        $parser = \class_exists(SchemaParser::class)
            ? new SchemaParser()
            : Parser::fromGrammar(static::grammar());

        return new static($parser);
    }

    /**
     * @return Readable
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public static function grammar(): Readable
    {
        return File::fromPathname(self::GRAMMAR_FILE);
    }

    /**
     * @param Readable $readable
     * @return NodeInterface
     * @throws \Railt\Compiler\Exception\ParserException
     * @throws \RuntimeException
     */
    public function parse(Readable $readable): NodeInterface
    {
        return $this->runtime->parse($readable);
    }
}
