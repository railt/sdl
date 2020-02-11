<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\UnionTypeInterface;
use Railt\TypeSystem\Type\UnionType;

/**
 * Class UnionTypeDefinitionContext
 */
final class UnionTypeDefinitionContext extends TypeDefinitionContext
{
    /**
     * @param array $args
     * @return UnionTypeInterface
     * @throws \Throwable
     */
    public function build(array $args): UnionTypeInterface
    {
        return new UnionType($this->getName(), [
            'description' => $this->description($this->ast),
        ]);
    }
}
