<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast;

use Railt\SDL\Ast\Dependent\FieldDefinitionNode;

/**
 * Interface ProvidesFields
 */
interface ProvidesFieldNodes
{
    /**
     * @return iterable|FieldDefinitionNode[]
     */
    public function getFieldNodes(): iterable;
}
