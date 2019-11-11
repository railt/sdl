<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\TypeSystem\EnumValue;
use Railt\SDL\Ast\Definition\EnumValueDefinitionNode;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;

/**
 * @property-read EnumValueDefinitionNode $ast
 */
class EnumValueBuilder extends TypeBuilder
{
    /**
     * @return DefinitionInterface
     */
    public function build(): DefinitionInterface
    {
        $value = new EnumValue([
            'name'        => $this->ast->name->value,
            'value'       => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
        ]);

        return $value;
    }

}
