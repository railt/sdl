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
use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\TypeSystem\Type\ScalarType;

/**
 * Class ScalarTypeDefinitionContext
 */
class ScalarTypeDefinitionContext extends NamedTypeContext
{
    /**
     * @param DefinitionNode|ScalarTypeDefinitionNode $ast
     * @return ScalarTypeInterface
     * @throws \Throwable
     */
    public function build(DefinitionNode $ast): ScalarTypeInterface
    {
        $scalar = new ScalarType($ast->name->value, [
            'description' => $this->descriptionOf($ast),
        ]);

        return $scalar;
    }
}
