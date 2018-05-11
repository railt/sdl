<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Record;

use Railt\Compiler\Parser\Ast\RuleInterface;

/**
 * Class TypeDefinitionRecord
 */
class TypeDefinitionRecord extends DefinitionRecord implements ProvidesDefinitions
{
    /**
     * @return iterable|RuleInterface[]
     */
    public function getDefinitions(): iterable
    {
        $rule = $this->ast->find('#ChildrenDefinitions');

        if ($rule === null) {
            return [];
        }

        return $rule->getChildren();
    }
}
