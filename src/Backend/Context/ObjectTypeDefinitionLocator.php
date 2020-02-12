<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use Railt\TypeSystem\Type\ObjectType;

/**
 * Class ObjectTypeDefinitionContext
 */
final class ObjectTypeDefinitionLocator extends TypeDefinitionLocator
{
    /**
     * @param array $args
     * @return ObjectTypeInterface
     * @throws \Throwable
     */
    public function build(array $args): ObjectTypeInterface
    {
        return new ObjectType($this->getName(), [
            'description' => $this->description($this->ast),
        ]);
    }
}
