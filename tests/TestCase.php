<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Parser\Ast\NodeInterface;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\SDL\Compiler;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @param string $dir
     * @param string $ext
     * @return iterable|Readable[]
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \InvalidArgumentException
     */
    protected function read(string $dir, string $ext): iterable
    {
        $files = (new Finder())->files()->in($dir)->name('*.' . $ext);

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $isValid = $file->getBasename()[0] !== '-';

            yield $isValid => File::fromSplFileInfo($file);
        }
    }

    /**
     * @return Compiler
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    protected function getCompiler(): Compiler
    {
        return new Compiler();
    }

    /**
     * @param string $type
     * @param string $code
     * @return TypeDefinition
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    protected function compile(string $code, string $type = 'Type'): TypeDefinition
    {
        $compiler   = $this->getCompiler();
        $document   = $compiler->compile(File::fromSources(\trim($code)));
        $definition = $document->getDefinition($type);

        $this->assertNotNull($definition);

        return $definition;
    }

    /**
     * @param string $expected
     * @param null|NodeInterface $actual
     */
    protected function assertAst(string $expected, ?NodeInterface $actual): void
    {
        $this->assertSame(
            $this->formatXml($expected),
            $this->formatXml((string)$actual)
        );
    }

    /**
     * @param string $text
     * @return string
     */
    private function formatXml(string $text): string
    {
        $lines = \explode("\n", \str_replace("\r", '', $text));
        $lines = \array_map('\\trim', $lines);
        $lines = \array_filter($lines);

        return \implode("\n", $lines);
    }
}
