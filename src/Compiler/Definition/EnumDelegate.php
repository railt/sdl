<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Definition;

use Railt\Reflection\Definition\DirectiveDefinition;

/**
 * Class EnumDelegate
 */
class EnumDelegate extends DefinitionDelegate
{
    /**
     * @return string
     */
    protected function getTypeDefinition(): string
    {
        return DirectiveDefinition::class;
    }
}
