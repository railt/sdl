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
 * Class ObjectDefinitionRecord
 */
class ObjectDefinitionRecord extends TypeDefinitionRecord implements ProvidesRelations
{
    public function getRelations(): iterable
    {
        $implements = $this->ast->find('#Implements');

        if ($implements) {
            yield from $this->extractInterfaces($implements);
        }
    }

    /**
     * @param RuleInterface $implements
     * @return \Generator
     */
    private function extractInterfaces(RuleInterface $implements): \Generator
    {
        foreach ($implements->getChildren() as $interface) {
            yield $this->readName($interface);
        }
    }
}
