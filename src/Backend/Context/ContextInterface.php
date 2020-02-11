<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;

/**
 * Interface ContextInterface
 */
interface ContextInterface
{
    /**
     * @param LocalContextInterface $context
     * @return void
     */
    public function addType(LocalContextInterface $context): void;

    /**
     * @param string $type
     * @return bool
     */
    public function hasType(string $type): bool;

    /**
     * @param string $type
     * @param array|string[] $args
     * @return TypeInterface
     */
    public function fetchType(string $type, array $args = []): TypeInterface;

    /**
     * @param LocalContextInterface $context
     * @return void
     */
    public function addDirective(LocalContextInterface $context): void;

    /**
     * @param string $type
     * @return bool
     */
    public function hasDirective(string $type): bool;

    /**
     * @param string $type
     * @param array|string[] $args
     * @return DirectiveInterface
     */
    public function fetchDirective(string $type, array $args = []): DirectiveInterface;

    /**
     * @return SchemaInterface
     */
    public function getSchema(): SchemaInterface;
}
