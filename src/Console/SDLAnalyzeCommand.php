<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Console;

use Railt\Console\Command;
use Railt\Io\File;

/**
 * Class SDLAnalyzeCommand
 */
class SDLAnalyzeCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'sdl:analyze 
        {schema : GraphQL SDL file}
        {--find= : Root AST rule name}';

    /**
     * @var string
     */
    protected $description = 'Parse and render GraphQL SDL AST';

    /**
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function handle(): void
    {
        $ast = (new Parser())->parse(File::fromPathname($this->argument('schema')));

        if ($root = $this->option('find')) {
            foreach ($ast->find((string)$root) as $child) {
                $this->writeln((string)$child);
            }
        } else {
            $this->writeln((string)$ast);
        }
    }
}
