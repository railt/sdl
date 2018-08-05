<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast\Common;

use Railt\SDL\Compiler\Ast\Dependent\FieldDefinitionNode;

/**
 * Trait FieldsProviders
 */
trait FieldsProviders
{
    /**
     * @return iterable|FieldDefinitionNode[]
     */
    public function getFields(): iterable
    {
        $fields = $this->first('FieldDefinitions', 1);

        if ($fields) {
            foreach ($fields as $field) {
                yield $field;
            }
        }
    }
}
