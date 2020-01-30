<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend;

use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Exception\RuntimeErrorException;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Interface HashTableInterface
 */
interface HashTableInterface
{
    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function add(string $name, $value): self;

    /**
     * @param string $name
     * @param Node|null $ctx
     * @return ValueInterface
     * @throws NotAccessibleException
     * @throws RuntimeErrorException
     * @throws \RuntimeException
     */
    public function get(string $name, Node $ctx = null): ValueInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
}
