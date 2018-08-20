<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language;

use Railt\Reflection\Contracts\TypeInterface as TypeInterface;
use Railt\Reflection\Type;

/**
 * Class UnionTestCase
 */
class UnionTestCase extends TypeDefinitionTestCase
{
    /**
     * @return string
     */
    protected function getSources(): string
    {
        return 'union A = Int';
    }

    /**
     * @return string
     */
    protected function getTypeName(): string
    {
        return 'A';
    }

    /**
     * @return Type
     */
    protected function getType(): TypeInterface
    {
        return Type::of(Type::UNION);
    }
}
