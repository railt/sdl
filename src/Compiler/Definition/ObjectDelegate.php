<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Definition;

use Railt\Reflection\Definition\ObjectDefinition;

/**
 * Class ObjectDelegate
 */
class ObjectDelegate extends DefinitionDelegate
{
    /**
     * @return string
     */
    protected function getTypeDefinition(): string
    {
        return ObjectDefinition::class;
    }
}
