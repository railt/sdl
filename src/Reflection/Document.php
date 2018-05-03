<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection;

use Railt\SDL\Reflection\Definition\TypeDefinition;
use Railt\SDL\Reflection\Invocation\Directive\HasDirectives;

/**
 * The Document is an object that contains information
 * about all types available in one same context.
 *
 * This can be, for example, a GraphQL schema file.
 */
interface Document extends Definition, HasDirectives
{
    /**
     * @return iterable|TypeDefinition[]
     */
    public function getTypeDefinitions(): iterable;

    /**
     * @param string $name
     * @return null|TypeDefinition
     */
    public function getTypeDefinition(string $name): ?TypeDefinition;

    /**
     * @param string $name
     * @return bool
     */
    public function hasTypeDefinition(string $name): bool;

    /**
     * @return int
     */
    public function getNumberOfTypeDefinition(): int;
}
