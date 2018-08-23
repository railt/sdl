<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL;

/**
 * Class DeprecationTestCase
 */
class DeprecationTestCase extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testEnumDeprecation(): void
    {
        $enum = $this->compile('
            enum Type @deprecated(reason: "A") {
                VALUE @deprecated(reason: "B")
            }
        ');

        $this->assertTrue($enum->isDeprecated());
        $this->assertSame('A', $enum->getDeprecationReason());

        $this->assertTrue($enum->getValue('VALUE')->isDeprecated());
        $this->assertSame('B', $enum->getValue('VALUE')->getDeprecationReason());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testInputDeprecation(): void
    {
        $input = $this->compile('
            input Type @deprecated(reason: "A") {
                field: String @deprecated(reason: "B")
            }
        ');

        $this->assertTrue($input->isDeprecated());
        $this->assertSame('A', $input->getDeprecationReason());

        $this->assertTrue($input->getField('field')->isDeprecated());
        $this->assertSame('B', $input->getField('field')->getDeprecationReason());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testInterfaceDeprecation(): void
    {
        $interface = $this->compile('
            interface Type @deprecated(reason: "A") {
                field(
                    arg: String 
                        @deprecated(reason: "B")
                ): String 
                    @deprecated(reason: "C")
            }
        ');

        $this->assertTrue($interface->isDeprecated());
        $this->assertSame('A', $interface->getDeprecationReason());

        $this->assertTrue($interface->getField('field')->isDeprecated());
        $this->assertSame('B', $interface->getField('field')->getDeprecationReason());

        $this->assertTrue($interface->getField('field')->getArgument('arg')->isDeprecated());
        $this->assertSame('C', $interface->getField('field')->getArgument('arg')->getDeprecationReason());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testObjectDeprecation(): void
    {
        $object = $this->compile('
            type Type @deprecated(reason: "A") {
                field(
                    arg: String 
                        @deprecated(reason: "B")
                ): String 
                    @deprecated(reason: "C")
            }
        ');

        $this->assertTrue($object->isDeprecated());
        $this->assertSame('A', $object->getDeprecationReason());

        $this->assertTrue($object->getField('field')->isDeprecated());
        $this->assertSame('B', $object->getField('field')->getDeprecationReason());

        $this->assertTrue($object->getField('field')->getArgument('arg')->isDeprecated());
        $this->assertSame('C', $object->getField('field')->getArgument('arg')->getDeprecationReason());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testScalarDeprecation(): void
    {
        $object = $this->compile('
            scalar Type @deprecated(reason: "A")
        ');

        $this->assertTrue($object->isDeprecated());
        $this->assertSame('A', $object->getDeprecationReason());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testSchemaDeprecation(): void
    {
        $object = $this->compile('
            schema Type @deprecated(reason: "A") {}
        ');

        $this->assertTrue($object->isDeprecated());
        $this->assertSame('A', $object->getDeprecationReason());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testUnionDeprecation(): void
    {
        $object = $this->compile('
            union Type @deprecated(reason: "A") = String | ID
        ');

        $this->assertTrue($object->isDeprecated());
        $this->assertSame('A', $object->getDeprecationReason());
    }
}
