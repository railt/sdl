<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Syntax;

use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\SDL\Compiler\Parser;
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
            $name          = \ltrim(\basename($file->getPathname(), '.graphqls'), '+-');
            $result[$name] = [$file, $isPositive];
        }

        return $result;
    }

    /**
     * @return Parser
     */
    protected function parser(): Parser
    {
        return new class() extends Parser {
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
}
