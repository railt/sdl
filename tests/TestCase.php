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
     * @param string $expected
     * @param null|NodeInterface $actual
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
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
