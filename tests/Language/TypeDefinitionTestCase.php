<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language;

use Railt\Io\File;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\Reflection\Contracts\TypeInterface as TypeInterface;
use Railt\Reflection\Type;
use Railt\Tests\SDL\TestCase;

/**
 * Class LanguageTestCase
 */
abstract class TypeDefinitionTestCase extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testTypeInheritance(): void
    {
        $definition = $this->compileType();

        $this->assertTrue($definition::typeOf(Type::ANY));
        $this->assertTrue($definition::typeOf(Type::of(Type::ANY)));
    }

    /**
     * @return TypeDefinition
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    protected function compileType(): TypeDefinition
    {
        return $this->compile($this->getSources(), $this->getTypeName());
    }

    /**
     * @return string
     */
    abstract protected function getSources(): string;

    /**
     * @return string
     */
    abstract protected function getTypeName(): string;

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testInheritance(): void
    {
        $definition = $this->compileType();
        $dictionary = $definition->getDictionary();

        $parent = $definition->getInheritedParent();

        if ($parent) {
            $this->assertTrue($definition->instanceOf($parent));
            $this->assertTrue($definition->extendsOf($parent));
            $this->assertTrue($definition->hasInheritance());
            $this->assertContains($definition, $parent->inheritedBy());

            return;
        }

        foreach ($dictionary->all() as $type) {
            if ($type->getName() === $definition->getName()) {
                continue;
            }

            if ($type::getType()->is(Type::ANY)) {
                $this->assertTrue($definition->instanceOf($type));
            } else {
                $this->assertFalse($definition->instanceOf($type));
            }

            $this->assertFalse($type->instanceOf($definition));

            $this->assertFalse($definition->extendsOf($type));
            $this->assertFalse($type->extendsOf($definition));

            $this->assertNotContains($type, $definition->inheritedBy());
            $this->assertNotContains($definition, $type->inheritedBy());
        }

        $this->assertFalse($definition->hasInheritance());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testDeprecation(): void
    {
        $definition = $this->compileType();

        $this->assertFalse($definition->isDeprecated());
        $this->assertSame('', $definition->getDeprecationReason());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testDirectives(): void
    {
        $definition = $this->compileType();

        $this->assertCount(0, $definition->getDirectives());
        $this->assertCount(0, $definition->getDirectives('some'));
        $this->assertFalse($definition->hasDirective('some'));
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function testTypeAddableIntoDictionary(): void
    {
        $definition = $this->compileType();
        $dictionary = $definition->getDictionary();

        $expected = $this->getCompiler()->compile(File::fromSources(''))->getDictionary()->all();

        $this->assertCount(\iterator_count($expected) + 1, $dictionary->all());
        $this->assertSame($definition, $dictionary->get($this->getTypeName()));
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testTypeFileSources(): void
    {
        $definition = $this->compileType();

        $this->assertSame(\trim($this->getSources()), $definition->getFile()->getContents());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testTypeFile(): void
    {
        $definition = $this->compileType();

        $this->assertNotSame(0, $definition->getLine());
        $this->assertNotSame(0, $definition->getColumn());
    }

    /**
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testTypeExists(): void
    {
        $this->assertNotNull($this->compileType());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testTypeName(): void
    {
        $this->assertSame($this->getTypeName(), $this->compileType()->getName());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testTypeIsSerializable(): void
    {
        $this->assertStringStartsWith($this->getTypeName(), (string)$this->compileType());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testTypeDescription(): void
    {
        $this->assertSame('', $this->compileType()->getDescription());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Reflection\Exception\TypeConflictException
     */
    public function testType(): void
    {
        $definition = $this->compileType();

        $this->assertSame($this->getType()->getName(), $definition::getType()->getName());
        $this->assertSame($this->getType(), $definition::getType());
        $this->assertTrue($definition::typeOf($this->getType()));
    }

    /**
     * @return TypeInterface
     */
    abstract protected function getType(): TypeInterface;
}
