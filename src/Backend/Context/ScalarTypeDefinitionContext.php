<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\ScalarTypeInterface;
use Railt\SDL\Frontend\Ast\Definition\Type\ScalarTypeDefinitionNode;
use Railt\TypeSystem\Type\ScalarType;

/**
 * @property-read ScalarTypeDefinitionNode $ast
 */
class ScalarTypeDefinitionContext extends TypeDefinitionContext
{
    /**
     * @param array $args
     * @return ScalarTypeInterface
     * @throws \Throwable
     */
    public function resolve(array $args = []): ScalarTypeInterface
    {
        $scalar = new ScalarType($this->ast->name->value, [
            'description' => $this->descriptionOf($this->ast),
        ]);

        return $scalar;
    }
}
