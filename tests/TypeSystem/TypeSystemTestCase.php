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
use Railt\SDL\IR\Type\TypeInterface;
use Railt\Tests\SDL\TestCase;

/**
 * Class TypeSystemTestCase
 */
abstract class TypeSystemTestCase extends TestCase
{
    /**
     * @return array
     */
    abstract public function provider(): array;

    /**
     * @dataProvider provider
     * @param TypeInterface $type
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testInstanceOfAny(TypeInterface $type): void
    {
        $this->assertTrue($type->typeOf(Type::any()));
    }
}
