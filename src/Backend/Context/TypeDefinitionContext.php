<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\SDL\Frontend\Ast\Definition\Type\TypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Identifier;
use Railt\SDL\Frontend\Ast\TypeName;

/**
 * @property-read TypeDefinitionNode $ast
 */
abstract class TypeDefinitionContext extends DefinitionContext implements TypeDefinitionContextInterface
{
    /**
     * @var array|string[]
     */
    private ?array $genericArguments = null;

    /**
     * {@inheritDoc}
     */
    public function getGenericArguments(): array
    {
        if ($this->genericArguments === null) {
            $this->genericArguments = [];

            if ($this->ast->name instanceof TypeName) {
                $fn = fn(Identifier $id): string => $id->value;

                $this->genericArguments = \array_map($fn, $this->ast->name->arguments);
            }
        }

        return $this->genericArguments;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->ast->name->value;
    }
}
