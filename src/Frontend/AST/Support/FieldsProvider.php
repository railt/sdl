<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Support;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Dependent\FieldDefinitionNode;

/**
 * Trait FieldsProvider
 */
trait FieldsProvider
{
    /**
     * @return iterable|FieldDefinitionNode[]
     */
    public function getFieldNodes(): iterable
    {
        $fields = $this->first('FieldDefinitions', 1);

        if ($fields instanceof RuleInterface) {
            yield from $fields;
        }
    }
}
