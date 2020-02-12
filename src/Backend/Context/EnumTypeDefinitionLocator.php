<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;
use Railt\TypeSystem\Type\EnumType;

/**
 * Class EnumTypeDefinitionContext
 */
final class EnumTypeDefinitionLocator extends TypeDefinitionLocator
{
    /**
     * @param array $args
     * @return EnumTypeInterface
     * @throws \Throwable
     */
    public function build(array $args): EnumTypeInterface
    {
        return new EnumType($this->getName(), [
            'description' => $this->description($this->ast),
        ]);
    }
}
