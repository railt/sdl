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
 * Class OffsetsTestCase
 */
class OffsetsTestCase extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testDirectiveOffset(): void
    {
        $directive = $this->compile('
            #
            # Object example
            #
            "Object description"    
            directive @Type(        # 5
                "argument description"
                arg: String         # 7
            ) on FIELD
        ');

        $this->assertEquals(5, $directive->getLine());
        $this->assertEquals(24, $directive->getColumn());

        $this->assertEquals(7, $directive->getArgument('arg')->getLine());
        $this->assertEquals(17, $directive->getArgument('arg')->getColumn());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testEnumOffset(): void
    {
        $enum = $this->compile('
            #
            # Enum example
            #
            "Enum description"  
            enum Type {         # 5
                "Value description"
                VALUE           # 7
            }
        ');

        $this->assertEquals(5, $enum->getLine());
        $this->assertEquals(18, $enum->getColumn());

        $this->assertEquals(7, $enum->getValue('VALUE')->getLine());
        $this->assertEquals(17, $enum->getValue('VALUE')->getColumn());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testInputOffset(): void
    {
        $input = $this->compile('
            #
            # Input example
            #
            "Input description"
            input Type {        # 5
                "Field description"
                field: String   # 7
            }
        ');

        $this->assertEquals(5, $input->getLine());
        $this->assertEquals(19, $input->getColumn());

        $this->assertEquals(7, $input->getField('field')->getLine());
        $this->assertEquals(17, $input->getField('field')->getColumn());
    }


    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testInterfaceOffset(): void
    {
        $interface = $this->compile('
            #
            # Interface example
            #
            "Interface description"
            interface Type {        # 5
                "Field description"
                field(              # 7
                    "Argument description"
                    arg: String     # 9
                ): String
            }
        ');

        $this->assertEquals(5, $interface->getLine());
        $this->assertEquals(23, $interface->getColumn());

        $this->assertEquals(7, $interface->getField('field')->getLine());
        $this->assertEquals(17, $interface->getField('field')->getColumn());

        $this->assertEquals(9, $interface->getField('field')->getArgument('arg')->getLine());
        $this->assertEquals(21, $interface->getField('field')->getArgument('arg')->getColumn());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testObjectOffset(): void
    {
        $object = $this->compile('
            #
            # Object example
            #
            "Object description"
            type Type {             # 5
                "Field description"
                field(              # 7
                    "Argument description"
                    arg: String     # 9
                ): String
            }
        ');

        $this->assertEquals(5, $object->getLine());
        $this->assertEquals(18, $object->getColumn());

        $this->assertEquals(7, $object->getField('field')->getLine());
        $this->assertEquals(17, $object->getField('field')->getColumn());

        $this->assertEquals(9, $object->getField('field')->getArgument('arg')->getLine());
        $this->assertEquals(21, $object->getField('field')->getArgument('arg')->getColumn());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testScalarOffset(): void
    {
        $scalar = $this->compile('
            #
            # Scalar example
            #
            "Scalar description"
            scalar Type             # 5
        ');

        $this->assertEquals(5, $scalar->getLine());
        $this->assertEquals(20, $scalar->getColumn());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testSchemaOffset(): void
    {
        $schema = $this->compile('
            #
            # Schema example
            #
            "Schema description"
            schema Type {}          # 5
        ');

        $this->assertEquals(5, $schema->getLine());
        $this->assertEquals(20, $schema->getColumn());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testUnionOffset(): void
    {
        $union = $this->compile('
            #
            # Union example
            #
            "Union description"
            union Type = String | Int # 5
        ');

        $this->assertEquals(5, $union->getLine());
        $this->assertEquals(19, $union->getColumn());
    }
}
