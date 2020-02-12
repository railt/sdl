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
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionLocationNode as LocationNode;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\TypeSystem\Directive;

/**
 * @property-read DirectiveDefinitionNode $ast
 */
final class DirectiveDefinitionLocator extends DefinitionLocator
{
    /**
     * @param array $args
     * @return DirectiveInterface
     * @throws \Throwable
     */
    public function build(array $args): DirectiveInterface
    {
        $locations = \array_map(fn(LocationNode $loc): string => $loc->name->value, $this->ast->locations);

        return new Directive($this->getName(), [
            'description' => $this->description($this->ast),
            'repeatable'  => $this->ast->repeatable !== null,
            'locations'   => $locations,
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->ast->name->value;
    }

    /**
     * @return array
     */
    public function getGenericArguments(): array
    {
        return [];
    }
}
