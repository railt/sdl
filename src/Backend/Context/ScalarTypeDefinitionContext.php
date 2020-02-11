<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\ScalarTypeInterface;
use Railt\TypeSystem\Type\ScalarType;

/**
 * Class ScalarTypeDefinitionContext
 */
final class ScalarTypeDefinitionContext extends TypeDefinitionContext
{
    /**
     * @param array $args
     * @return ScalarTypeInterface
     * @throws \Throwable
     */
    public function build(array $args): ScalarTypeInterface
    {
        return new ScalarType($this->getName(), [
            'description' => $this->description($this->ast),
        ]);
    }
}
