<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\InputTypeInterface;
use Railt\TypeSystem\Type\InputObjectType;

/**
 * Class InputObjectTypeDefinitionContext
 */
final class InputObjectTypeDefinitionLocator extends TypeDefinitionLocator
{
    /**
     * @param array $args
     * @return InputTypeInterface
     * @throws \Throwable
     */
    public function build(array $args): InputTypeInterface
    {
        return new InputObjectType($this->getName(), [
            'description' => $this->description($this->ast),
        ]);
    }
}
