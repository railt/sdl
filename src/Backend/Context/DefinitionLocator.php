<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\SDL\Frontend\Ast\DefinitionNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class DefinitionContext
 */
abstract class DefinitionLocator implements TypeLocatorInterface
{
    /**
     * @var DefinitionNode
     */
    protected DefinitionNode $ast;

    /**
     * DefinitionContext constructor.
     *
     * @param DefinitionNode $ast
     */
    public function __construct(DefinitionNode $ast)
    {
        $this->ast = $ast;
    }

    /**
     * @param Node $node
     * @return string|null
     */
    protected function description(Node $node): ?string
    {
        if (\property_exists($node, 'description') && $node->description) {
            return $node->description->value;
        }

        return null;
    }
}
