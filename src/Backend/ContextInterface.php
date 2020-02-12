<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use Railt\SDL\Backend\Context\TypeLocatorInterface;

/**
 * Interface ContextInterface
 */
interface ContextInterface
{
    /**
     * @param TypeLocatorInterface $context
     * @return void
     */
    public function addType(TypeLocatorInterface $context): void;

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
     * @param TypeLocatorInterface $context
     * @return void
     */
    public function addDirective(TypeLocatorInterface $context): void;

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
