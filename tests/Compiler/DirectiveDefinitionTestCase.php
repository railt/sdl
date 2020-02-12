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
use Railt\SDL\Tests\TestCase;
use Railt\TypeSystem\Schema;

/**
 * Class DirectiveDefinitionTestCase
 */
class DirectiveDefinitionTestCase extends TestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testCompilable(): void
    {
        $schema = $this->schema();

        $this->assertTrue($schema->hasDirective('example'));
    }

    /**
     * @return Schema
     * @throws \Throwable
     */
    private function schema(): Schema
    {
        return $this->compile(/** @lang GraphQL */ '
            scalar Example
            
            directive @example on 
                | OBJECT
                
            "Short Description" directive @description1 on 
                | OBJECT
                
            """
            Long Description
            """ 
            directive @description2 on 
                | OBJECT
                
            directive @locations on 
                | INPUT_FIELD_DEFINITION
                | ARGUMENT_DEFINITION
                | FIELD_DEFINITION
                | ENUM_VALUE 
                
            directive @arguments(a: Example b: Example) on 
                | OBJECT
                
            directive @repeatable repeatable on 
                | OBJECT 
        ');
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testName(): void
    {
        $schema = $this->schema();

        $this->assertSame('example', $schema->getDirective('example')->getName());
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testDuplication(): void
    {
        $this->expectException(GraphQLException::class);

        $this->compile('
            directive @example on FIELD_DEFINITION
            directive @example on FIELD_DEFINITION
        ');
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testEmptyDescription(): void
    {
        $schema = $this->schema();

        $this->assertNull($schema->getDirective('example')->getDescription());
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testShortDescription(): void
    {
        $schema = $this->schema();

        $this->assertSame('Short Description',
            $schema->getDirective('description1')->getDescription()
        );
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testMultilineDescription(): void
    {
        $schema = $this->schema();

        $this->assertSame('Long Description',
            \trim($schema->getDirective('description2')->getDescription())
        );
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testRepeatable(): void
    {
        $schema = $this->schema();

        $this->assertFalse($schema->getDirective('example')->isRepeatable());
        $this->assertTrue($schema->getDirective('repeatable')->isRepeatable());
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testLocations(): void
    {
        $schema = $this->schema();

        $this->assertEquals([
            'OBJECT',
        ], $schema->getDirective('example')->getLocations());

        $this->assertEquals([
            'INPUT_FIELD_DEFINITION',
            'ARGUMENT_DEFINITION',
            'FIELD_DEFINITION',
            'ENUM_VALUE',
        ], $schema->getDirective('locations')->getLocations());
    }
}
