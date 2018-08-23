<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL;

class DescriptionsTestCase extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testDirectiveDescription(): void
    {
        $this->assertSame('Description',
            $this->compile('"""Description""" directive @Type on OBJECT')->getDescription());

        $this->assertSame('Description',
            $this->compile('"Description" directive @Type on OBJECT')->getDescription());

        //
        // Arguments
        //

        $this->assertSame('Description',
            $this->compile('directive @Type("""Description""" arg: ID) on OBJECT')
                ->getArgument('arg')->getDescription());

        $this->assertSame('Description',
            $this->compile('directive @Type("Description" arg: ID) on OBJECT')
                ->getArgument('arg')->getDescription());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testEnumDescription(): void
    {
        $this->assertSame('Description',
            $this->compile('"""Description""" enum Type {}')->getDescription());

        $this->assertSame('Description',
            $this->compile('"Description" enum Type {}')->getDescription());

        //
        // Values
        //

        $this->assertSame('Description',
            $this->compile('enum Type { """Description""" Value }')
                ->getValue('Value')->getDescription());

        $this->assertSame('Description',
            $this->compile('enum Type { "Description" Value}')
                ->getValue('Value')->getDescription());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testInputDescription(): void
    {
        $this->assertSame('Description',
            $this->compile('"""Description""" input Type {}')->getDescription());

        $this->assertSame('Description',
            $this->compile('"Description" input Type {}')->getDescription());

        //
        // Input field
        //

        $this->assertSame('Description',
            $this->compile('input Type { """Description""" field: ID }')
                ->getField('field')->getDescription());

        $this->assertSame('Description',
            $this->compile('input Type { "Description" field: ID }')
                ->getField('field')->getDescription());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testInterfaceDescription(): void
    {
        $this->assertSame('Description',
            $this->compile('"""Description""" interface Type {}')->getDescription());

        $this->assertSame('Description',
            $this->compile('"Description" interface Type {}')->getDescription());

        //
        // Interface field
        //

        $this->assertSame('Description',
            $this->compile('interface Type { """Description""" field: ID }')
                ->getField('field')->getDescription());

        $this->assertSame('Description',
            $this->compile('interface Type { "Description" field: ID }')
                ->getField('field')->getDescription());

        //
        // Interface argument of field
        //

        $this->assertSame('Description',
            $this->compile('interface Type { field("""Description""" a: ID): ID }')
                ->getField('field')->getArgument('a')->getDescription());

        $this->assertSame('Description',
            $this->compile('interface Type { field("Description" a: ID): ID }')
                ->getField('field')->getArgument('a')->getDescription());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testObjectDescription(): void
    {
        $this->assertSame('Description',
            $this->compile('"""Description""" type Type {}')->getDescription());

        $this->assertSame('Description',
            $this->compile('"Description" type Type {}')->getDescription());

        //
        // Object field
        //

        $this->assertSame('Description',
            $this->compile('type Type { """Description""" field: ID }')
                ->getField('field')->getDescription());

        $this->assertSame('Description',
            $this->compile('type Type { "Description" field: ID }')
                ->getField('field')->getDescription());

        //
        // Object argument of field
        //

        $this->assertSame('Description',
            $this->compile('type Type { field("""Description""" a: ID): ID }')
                ->getField('field')->getArgument('a')->getDescription());

        $this->assertSame('Description',
            $this->compile('type Type { field("Description" a: ID): ID }')
                ->getField('field')->getArgument('a')->getDescription());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testScalarDescription(): void
    {
        $this->assertSame('Description',
            $this->compile('"""Description""" scalar Type')->getDescription());

        $this->assertSame('Description',
            $this->compile('"Description" scalar Type')->getDescription());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testSchemaDescription(): void
    {
        $this->assertSame('Description',
            $this->compile('"""Description""" schema Type {}')->getDescription());

        $this->assertSame('Description',
            $this->compile('"Description" schema Type {}')->getDescription());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testUnionDescription(): void
    {
        $this->assertSame('Description',
            $this->compile('"""Description""" union Type = Int')->getDescription());

        $this->assertSame('Description',
            $this->compile('"Description" union Type = Int')->getDescription());
    }
}
