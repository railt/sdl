<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder\Definition;

use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Definition\DirectiveDefinition;

/**
 * Class DirectiveBuilder
 */
class DirectiveBuilder extends TypeDefinitionBuilder
{
    /**
     * @return Definition
     * @throws \Railt\SDL\Exception\SyntaxException
     */
    public function build(): Definition
    {
        $directive = $this->bind(new DirectiveDefinition($this->document, $this->getName()));



        return $directive;
    }
}
