<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;

/**
 * @property-read NamedTypeNode $ast
 */
abstract class NamedTypeContext extends DefinitionContext implements TypeDefinitionContextInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->ast->name->value;
    }
}
