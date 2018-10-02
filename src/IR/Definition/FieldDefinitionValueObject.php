<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Definition;

/**
 * @property array|ArgumentDefinitionValueObject[] $arguments
 * @property array|DirectiveInvocationValueObject[] $directives
 */
class FieldDefinitionValueObject extends TypeDefinitionValueObject
{
    /**
     * @var array
     */
    protected $attributes = [
        'arguments'  => [],
        'directives' => [],
    ];
}
