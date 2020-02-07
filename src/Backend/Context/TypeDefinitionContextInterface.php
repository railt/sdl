<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\TypeSystem\Directive;
use Railt\TypeSystem\Type\NamedType;

/**
 * @method Directive|NamedType resolve(array $args = [])
 */
interface TypeDefinitionContextInterface extends DefinitionContextInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}
