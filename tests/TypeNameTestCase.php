<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL;

use Railt\SDL\IR\Type\Name;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Class TypeNameTestCase
 */
class TypeNameTestCase extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSimpleStringName(): void
    {
        $name = Name::new('Example');
        $this->assertFalse($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example/Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example')));
        $this->assertTrue($name->is(Name::new('/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGlobalStringName(): void
    {
        $name = Name::new('/Example');
        $this->assertTrue($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example')));
        $this->assertTrue($name->is(Name::new('/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSimpleStringNameWithNamespace(): void
    {
        $name = Name::new('Example/Example');
        $this->assertFalse($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example/Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example/Example/Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example/Example')));
        $this->assertTrue($name->is(Name::new('/Example/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGlobalStringNameWithNamespace(): void
    {
        $name = Name::new('/Example/Example');
        $this->assertTrue($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example/Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example/Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example/Example')));
        $this->assertTrue($name->is(Name::new('/Example/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSimpleEmptyName(): void
    {
        $name = Name::new('');
        $this->assertFalse($name->isGlobal());
        $this->assertEquals('', $name->getName());
        $this->assertEquals('', $name->getFullyQualifiedName());
        $this->assertEquals('Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('')));
        $this->assertTrue($name->is(Name::new('/')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGlobalEmptyName(): void
    {
        $name = Name::new('/');
        $this->assertTrue($name->isGlobal());
        $this->assertEquals('', $name->getName());
        $this->assertEquals('', $name->getFullyQualifiedName());
        $this->assertEquals('', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::empty()));
        $this->assertTrue($name->is(Name::empty(true)));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSimpleStringNameWrapper(): void
    {
        $name = Name::new(Name::new('Example'));
        $this->assertFalse($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example/Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example')));
        $this->assertTrue($name->is(Name::new('/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGlobalStringNameWrapper(): void
    {
        $name = Name::new(Name::new('/Example'));
        $this->assertTrue($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example')));
        $this->assertTrue($name->is(Name::new('/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSimpleArrayName(): void
    {
        $name = Name::new(['Example', 'Example']);
        $this->assertFalse($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example/Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example/Example/Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example/Example')));
        $this->assertTrue($name->is(Name::new('/Example/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGlobalArrayName(): void
    {
        $name = Name::new(['Example', 'Example'], true);
        $this->assertTrue($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example/Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example/Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example/Example')));
        $this->assertTrue($name->is(Name::new('/Example/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSimpleIterableName(): void
    {
        $name = Name::new((function () {
            yield 'Example';
            yield 'Example';
        })());
        $this->assertFalse($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example/Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example/Example/Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example/Example')));
        $this->assertTrue($name->is(Name::new('/Example/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGlobalIterableName(): void
    {
        $name = Name::new((function () {
            yield 'Example';
            yield 'Example';
        })(), true);
        $this->assertTrue($name->isGlobal());
        $this->assertEquals('Example', $name->getName());
        $this->assertEquals('Example/Example', $name->getFullyQualifiedName());
        $this->assertEquals('Example/Example', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('Example/Example')));
        $this->assertTrue($name->is(Name::new('/Example/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSimpleIntName(): void
    {
        $name = Name::new(42);
        $this->assertFalse($name->isGlobal());
        $this->assertEquals('42', $name->getName());
        $this->assertEquals('42', $name->getFullyQualifiedName());
        $this->assertEquals('Example/42', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('42')));
        $this->assertTrue($name->is(Name::new('/42')));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGlobalIntName(): void
    {
        $name = Name::new(42, true);
        $this->assertTrue($name->isGlobal());
        $this->assertEquals('42', $name->getName());
        $this->assertEquals('42', $name->getFullyQualifiedName());
        $this->assertEquals('42', $name->in(Name::new('Example'))->getFullyQualifiedName());
        $this->assertTrue($name->is(Name::new('42')));
        $this->assertTrue($name->is(Name::new('/42')));
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testInvalidTypeName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Name::new(new \stdClass());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testNameTypesMatching(): void
    {
        $this->assertTrue(Name::isValid('string'));
        $this->assertTrue(Name::isValid(['array']));
        $this->assertTrue(Name::isValid(new \ArrayIterator(['array'])));
        $this->assertTrue(Name::isValid(null));
        $this->assertTrue(Name::isValid(42));
        $this->assertFalse(Name::isValid(new \stdClass()));
        $this->assertFalse(Name::isValid(function () {}));
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNameIterator(): void
    {
        $chunks = ['A', 'B', 'C'];
        $name = Name::new('A/B/C');

        $this->assertEquals($chunks, \iterator_to_array($name));
        $this->assertEquals($chunks, \iterator_to_array($name, false));
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNameLock(): void
    {
        $name = Name::new('A');

        $this->assertEquals($name, $name->lock());
        $this->assertTrue($name->isGlobal());
        $this->assertEquals('A', (string)$name->in('Prefix'));

        $this->assertEquals($name, $name->lock());
        $this->assertTrue($name->isGlobal());
        $this->assertEquals('A/Suffix', (string)$name->append('Suffix'));

        $this->assertEquals($name, $name->unlock());
        $this->assertFalse($name->isGlobal());
        $this->assertEquals('Prefix/A', (string)$name->in('Prefix'));

        $this->assertEquals($name, $name->unlock());
        $this->assertFalse($name->isGlobal());
        $this->assertEquals('A/Suffix', (string)$name->append('Suffix'));
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testJsonable(): void
    {
        $this->assertEquals('"Example"', \json_encode(Name::new('/Example')));
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSize(): void
    {
        $this->assertCount(0, Name::empty());
        $this->assertCount(0, Name::empty(true));
        $this->assertCount(1, Name::fromString('Example'));
        $this->assertCount(2, Name::fromString('Example/Example'));
        $this->assertCount(3, Name::fromArray([1, 2, 3]));
        $this->assertCount(3, Name::fromArray([1, 2, 3]));
    }
}
