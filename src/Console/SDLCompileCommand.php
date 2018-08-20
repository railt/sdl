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
use Railt\Io\Exception\NotReadableException;
use Railt\Io\File;
use Railt\SDL\Compiler;

/**
 * Class SDLCompileCommand
 */
class SDLCompileCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'sdl:compile 
        {schema : GraphQL SDL file}
        {--out= : Output directory}';

    /**
     * @var string
     */
    protected $description = 'Parse and compile GraphQL SDL file';

    /**
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    public function handle(): void
    {
        $schema = File::fromPathname($this->argument('schema'));

        $document   = (new Compiler())->compile($schema);
        $dictionary = $document->getDictionary();

        $result = [];

        foreach ($dictionary->all() as $type) {
            $result[] = $type;
        }

        $output = $this->option('out') ?: \dirname($schema->getPathname());

        if (! \is_dir($output)) {
            throw new NotReadableException('Output directory "' . $output . '" not exists');
        }

        $outputPathname = $output . '/' . \basename($schema->getPathname()) . '.json';

        \file_put_contents($outputPathname, \json_encode($result));
    }
}
