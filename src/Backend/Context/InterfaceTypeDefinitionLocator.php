<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use Railt\TypeSystem\Type\InterfaceType;

/**
 * Class InterfaceTypeDefinitionContext
 */
final class InterfaceTypeDefinitionLocator extends TypeDefinitionLocator
{
    /**
     * @param array $args
     * @return InterfaceTypeInterface
     * @throws \Throwable
     */
    public function build(array $args): InterfaceTypeInterface
    {
        return new InterfaceType($this->getName(), [
            'description' => $this->description($this->ast),
        ]);
    }
}
