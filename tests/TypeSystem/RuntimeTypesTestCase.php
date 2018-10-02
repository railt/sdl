<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\TypeSystem;

use Railt\SDL\IR\Type;
use Railt\SDL\IR\Type\Name;
use Railt\SDL\IR\Type\TypeInterface;

/**
 * Class RuntimeTypesTestCase
 */
class RuntimeTypesTestCase extends TypeSystemTestCase
{
    /**
     * @return array
     */
    public function provider(): array
    {
        $result = [];

        foreach (Type::RUNTIME_TYPES as $type) {
            $result[$type]               = [Type::new($type)];
            $result['ListOf' . $type]    = [Type::listOf(Type::new($type))];
            $result['NonNullOf' . $type] = [Type::nonNull(Type::new($type))];
        }

        return $result;
    }

    /**
     * @dataProvider provider
     * @param TypeInterface $type
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testIsBuiltin(TypeInterface $type): void
    {
        $this->assertFalse($type->isBuiltin());
    }

    /**
     * @dataProvider provider
     * @param TypeInterface $type
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testIsLocked(TypeInterface $type): void
    {
        $this->assertTrue($type->getName()->isGlobal());
    }

    /**
     * @dataProvider provider
     * @param TypeInterface $type
     * @throws \PHPUnit\Framework\Exception
     */
    public function testIsConstantName(TypeInterface $type): void
    {
        $fqn = $type->getName()->getFullyQualifiedName();

        $this->assertSame($fqn, $type->getName()->in(Name::new('Example'))->getFullyQualifiedName());
    }
}
