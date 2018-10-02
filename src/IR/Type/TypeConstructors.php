<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type;

use Railt\SDL\IR\Type\TypeConstructors\BuiltinTypeConstructors;
use Railt\SDL\IR\Type\TypeConstructors\GenericTypeConstructors;
use Railt\SDL\IR\Type\TypeConstructors\RuntimeTypeConstructors;

/**
 * Trait TypeConstructors
 */
trait TypeConstructors
{
    use BuiltinTypeConstructors;
    use GenericTypeConstructors;
    use RuntimeTypeConstructors;

    /**
     * @param string|iterable|TypeNameInterface $name
     * @param TypeInterface|null $of
     * @return TypeInterface
     */
    abstract protected static function new($name, TypeInterface $of = null): TypeInterface;
}
