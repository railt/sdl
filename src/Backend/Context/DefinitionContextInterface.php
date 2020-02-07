<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;

/**
 * Interface DefinitionContextInterface
 */
interface DefinitionContextInterface
{
    /**
     * @return array|string[]
     */
    public function getGenericArguments(): array;

    /**
     * @param array $args
     * @return DefinitionInterface
     */
    public function resolve(array $args = []): DefinitionInterface;
}
