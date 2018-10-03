<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Console;

use Railt\Compiler\Compiler;
use Railt\Console\Command;
use Railt\Io\File;

/**
 * Class SDLCompileCommand
 */
class SDLGrammarCompileCommand extends Command
{
    /**
     * @var string
     */
    private const SCHEMA_SDL_GRAMMAR = __DIR__ . '/../../resources/sdl/grammar.pp2';

    /**
     * @var string
     */
    protected $signature = 'sdl:grammar:compile';

    /**
     * @var string
     */
    protected $description = 'Compile GraphQL SDL Parser';

    /**
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \Throwable
     */
    public function handle(): void
    {
        Compiler::load(File::fromPathname(self::SCHEMA_SDL_GRAMMAR))
            ->setClassName('BaseParser')
            ->setNamespace('Railt\\SDL\\Frontend')
            ->saveTo(__DIR__ . '/../Frontend');

        $this->info('OK');
    }
}
