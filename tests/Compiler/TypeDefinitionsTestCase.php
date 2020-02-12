<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests\Compiler;

use Railt\SDL\Exception\GraphQLException;

/**
 * Class TypeDefinitionsTestCase
 */
class TypeDefinitionsTestCase extends CompilerTestCase
{
    /**
     * @dataProvider typesDataProvider
     *
     * @param string $type
     * @return void
     * @throws \Throwable
     */
    public function testCompilable(string $type): void
    {
        $schema = $this->compile("$type Example");

        $this->assertTrue($schema->hasType('Example'));
    }

    /**
     * @dataProvider typesDataProvider
     *
     * @param string $type
     * @return void
     * @throws \Throwable
     */
    public function testDuplication(string $type): void
    {
        $this->expectException(GraphQLException::class);

        $this->compile("
            $type Example
            $type Example
        ");
    }

    /**
     * @dataProvider typesDataProvider
     *
     * @param string $type
     * @return void
     * @throws \Throwable
     */
    public function testName(string $type): void
    {
        $schema = $this->compile("$type Example");

        $this->assertSame('Example', $schema->getType('Example')->getName());
    }

    /**
     * @dataProvider typesDataProvider
     *
     * @param string $type
     * @return void
     * @throws \Throwable
     */
    public function testEmptyDescription(string $type): void
    {
        $schema = $this->compile("$type Example");

        $this->assertNull($schema ->getType('Example')->getDescription());
    }

    /**
     * @dataProvider typesDataProvider
     *
     * @param string $type
     * @return void
     * @throws \Throwable
     */
    public function testShortDescription(string $type): void
    {
        $schema = $this->compile("\"descr\" $type Example");

        $this->assertSame('descr', $schema->getType('Example')->getDescription());
    }

    /**
     * @dataProvider typesDataProvider
     *
     * @param string $type
     * @return void
     * @throws \Throwable
     */
    public function testMultilineDescription(string $type): void
    {
        $schema = $this->compile("
            \"\"\"Multiline Type Description\"\"\"
            $type Example
        ");

        $this->assertSame('Multiline Type Description', $schema->getType('Example')->getDescription());
    }
}
