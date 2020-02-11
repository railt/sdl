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
use Railt\SDL\Frontend\Ast\Node;

/**
 * @property-read TypeDefinitionNode $ast
 */
abstract class TypeDefinitionContext extends DefinitionContext
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array|string[]
     */
    protected array $args;

    /**
     * TypeDefinitionContext constructor.
     *
     * @param TypeDefinitionNode $ast
     */
    public function __construct(TypeDefinitionNode $ast)
    {
        parent::__construct($ast);

        $this->name = $ast->name->value;
        $this->args = \array_map(fn (Identifier $id) => $id->value, $ast->name->arguments);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|string[]
     */
    public function getGenericArguments(): array
    {
        return $this->args;
    }
}
