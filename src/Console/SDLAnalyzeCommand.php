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
use Railt\Lexer\TokenInterface;
use Railt\SDL\Compiler;
use Railt\SDL\Console\Language\JsonLanguage;

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
        {--type= : Render only given type name}';

    /**
     * @var string
     */
    protected $description = 'Parse and render GraphQL SDL structure in JSON format';

    /**
     * @throws \InvalidArgumentException
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    public function handle(): void
    {
        $schema = File::fromPathname($this->argument('schema'));

        $document   = (new Compiler())->compile($schema);
        $dictionary = $document->getDictionary();

        if ($name = $this->option('type')) {
            if ($type = $dictionary->find(\trim($name))) {
                $this->render($type);
            } else {
                $this->error(\vsprintf('Type "%s" not found in file %s or could not be loaded', [
                    \trim($name),
                    $schema->getPathname(),
                ]));
            }

            return;
        }

        foreach ($dictionary->all() as $def) {
            if ($def->isBuiltin()) {
                continue;
            }

            $position = $def->getFile()->getPathname() . ':' . $def->getLine();

            $this->writeln('<fg=white;bg=green> ' . \str_repeat(' ', \strlen($position)) . ' </>');
            $this->writeln('<fg=white;bg=green> ' . $position . ' </>');
            $this->writeln('<fg=white;bg=green> ' . \str_repeat(' ', \strlen($position)) . ' </>');

            $this->render($def);
            $this->writeln("\n\n");
        }
    }

    /**
     * @param $value
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    private function render($value): void
    {
        $string = \json_encode($value, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE);
        $json   = new JsonLanguage();

        /** @var TokenInterface $token */
        foreach ($json->lex(File::fromSources($string, 'output.json')) as $token) {
            $this->write($json->highlight($token->getName(), $token->getValue()));
        }
    }
}
