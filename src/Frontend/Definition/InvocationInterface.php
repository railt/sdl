<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Definition;

use Railt\SDL\Frontend\Context\ContextInterface;
use Railt\SDL\IR\SymbolTable\PrimitiveInterface;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Interface InvocationInterface
 */
interface InvocationInterface extends PrimitiveInterface
{
    /**
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface;

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface;

    /**
     * @return iterable|InvocationInterface[]
     */
    public function getArguments(): iterable;

    /**
     * @param string $name
     * @param mixed $value
     * @return InvocationInterface
     */
    public function addArgument(string $name, $value): self;
}
