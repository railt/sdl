<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Dependent;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\Rule;
use Railt\SDL\Compiler\Ast\TypeHintNode;

/**
 * Class SchemaFieldDefinitionNode
 */
class SchemaFieldDefinitionNode extends Rule
{
    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->first('T_NAME', 1)->getValue();
    }

    /**
     * @return TypeHintNode|NodeInterface
     */
    public function getTypeHint(): TypeHintNode
    {
        return $this->first('TypeHint', 1);
    }
}
