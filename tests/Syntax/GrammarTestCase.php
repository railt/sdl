<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Syntax;

use Railt\Io\Exception\NotReadableException;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\SDL\Parser;
use Railt\Tests\SDL\TestCase;

/**
 * Class GrammarTestCase
 */
class GrammarTestCase extends TestCase
{
    /**
     * @return array
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function dataProvider(): array
    {
        $result = [];

        foreach ($this->read(__DIR__ . '/.resources', 'graphqls') as $isPositive => $file) {
            $name = \basename($file->getPathname(), '.graphqls');
            $result[\ltrim($name, '+-')] = [$file, $isPositive];
        }

        return $result;
    }

    /**
     * @return Parser
     */
    protected function parser(): Parser
    {
        return new class extends Parser {
            protected const PARSER_DELEGATES = [];
        };
    }

    /**
     * @dataProvider dataProvider
     * @param Readable $file
     * @param bool $valid
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSyntax(Readable $file, bool $valid): void
    {
        if ($valid) {
            $this->parser()->parse($file);
            $this->assertTrue(true);
        } else {
            $this->expectException(UnexpectedTokenException::class);
            $this->parser()->parse($file);
        }
    }

    /**
     * @dataProvider dataProvider
     * @param Readable $file
     * @param bool $valid
     * @throws \PHPUnit\Framework\Exception
     */
    public function testAst(Readable $file, bool $valid): void
    {
        if (! $valid) {
            $this->markTestSkipped('Could not test AST of negative sample');
        }

        try {
            $ast = File::fromPathname($file->getPathname() . '.ast.xml');

            $this->assertAst($ast->getContents(), $this->parser()->parse($file));
        } catch (NotReadableException $e) {
            $this->markTestIncomplete('Could not test AST because ast file did not provided');
        }
    }
}
