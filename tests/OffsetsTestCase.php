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

        $this->assertSame(5, $directive->getLine());
        $this->assertSame(24, $directive->getColumn());

        $this->assertSame(7, $directive->getArgument('arg')->getLine());
        $this->assertSame(17, $directive->getArgument('arg')->getColumn());
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

        $this->assertSame(5, $enum->getLine());
        $this->assertSame(18, $enum->getColumn());

        $this->assertSame(7, $enum->getValue('VALUE')->getLine());
        $this->assertSame(17, $enum->getValue('VALUE')->getColumn());
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

        $this->assertSame(5, $input->getLine());
        $this->assertSame(19, $input->getColumn());

        $this->assertSame(7, $input->getField('field')->getLine());
        $this->assertSame(17, $input->getField('field')->getColumn());
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

        $this->assertSame(5, $interface->getLine());
        $this->assertSame(23, $interface->getColumn());

        $this->assertSame(7, $interface->getField('field')->getLine());
        $this->assertSame(17, $interface->getField('field')->getColumn());

        $this->assertSame(9, $interface->getField('field')->getArgument('arg')->getLine());
        $this->assertSame(21, $interface->getField('field')->getArgument('arg')->getColumn());
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

        $this->assertSame(5, $object->getLine());
        $this->assertSame(18, $object->getColumn());

        $this->assertSame(7, $object->getField('field')->getLine());
        $this->assertSame(17, $object->getField('field')->getColumn());

        $this->assertSame(9, $object->getField('field')->getArgument('arg')->getLine());
        $this->assertSame(21, $object->getField('field')->getArgument('arg')->getColumn());
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

        $this->assertSame(5, $scalar->getLine());
        $this->assertSame(20, $scalar->getColumn());
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

        $this->assertSame(5, $schema->getLine());
        $this->assertSame(20, $schema->getColumn());
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

        $this->assertSame(5, $union->getLine());
        $this->assertSame(19, $union->getColumn());
    }
}
