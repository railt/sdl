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
 * Class TypeExtensionsTestCase
 */
class TypeExtensionsTestCase extends CompilerTestCase
{
    /**
     * @dataProvider typesDataProvider
     *
     * @param string $type
     * @return void
     * @throws \Throwable
     */
    public function testTypeNotFound(string $type): void
    {
        $this->expectException(GraphQLException::class);

        $this->compile("extend $type Example");
    }

    /**
     * @dataProvider typesDataProvider
     *
     * @param string $type
     * @return void
     * @throws \Throwable
     */
    public function testExtendable(string $type): void
    {
        $schema = $this->compile("
            $type Example
            
            ##########
            
            extend $type Example
        ");

        $this->assertTrue($schema->hasType('Example'));
    }
}
