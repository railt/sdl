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
use Railt\SDL\Backend\HashTable;
use Railt\SDL\Backend\HashTableInterface;

/**
 * Interface DefinitionContextInterface
 */
interface DefinitionContextInterface
{
    /**
     * @param HashTableInterface $vars
     * @return DefinitionInterface
     */
    public function resolve(HashTableInterface $vars): DefinitionInterface;
}
