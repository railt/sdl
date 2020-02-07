<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\TypeSystem\Directive;

/**
 * @property-read DirectiveDefinitionNode $ast
 */
class DirectiveDefinitionContext extends ObjectLikeTypeDefinitionContext
{
    /**
     * @param array $args
     * @return DefinitionInterface
     * @throws \Throwable
     */
    public function resolve(array $args = []): DefinitionInterface
    {
        $directive = new Directive($this->ast->name->value, [
            'description' => $this->descriptionOf($this->ast),
            'repeatable'  => $this->ast->repeatable !== null,
        ]);

        foreach ($this->ast->locations as $location) {
            $directive->addLocation($location->name->value);
        }

        foreach ($this->ast->arguments as $arg) {
            $directive->addArgument($this->buildArgumentDefinition($arg, $args));
        }

        return $directive;
    }
}
