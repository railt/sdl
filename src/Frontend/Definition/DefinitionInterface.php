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
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Interface DefinitionInterface
 */
interface DefinitionInterface
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
     * @return ContextInterface
     */
    public function getLocalContext(): ContextInterface;

    /**
     * @param string $name
     * @param TypeNameInterface $hint
     * @return DefinitionArgumentInterface
     */
    public function addArgument(string $name, TypeNameInterface $hint): DefinitionArgumentInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool;

    /**
     * @return iterable|DefinitionArgumentInterface[]
     */
    public function getArguments(): iterable;

    /**
     * @param string $name
     * @return DefinitionArgumentInterface
     */
    public function getArgument(string $name): DefinitionArgumentInterface;

    /**
     * @return bool
     */
    public function isGeneric(): bool;
}
