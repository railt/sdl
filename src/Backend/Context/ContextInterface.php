<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\SDL\Frontend\AST\Value\ValueInterface;

/**
 * Interface ContextInterface
 */
interface ContextInterface
{
    /**
     * Create inner context.
     *
     * @param TypeDefinition $type
     * @return ContextInterface
     */
    public function create(TypeDefinition $type): ContextInterface;

    /**
     * Select type definition from current context.
     *
     * @param string $type
     * @param array|ValueInterface[] $variables
     * @return TypeDefinition
     */
    public function get(string $type, array $variables = []): TypeDefinition;
}
