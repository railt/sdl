<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Record;

use Railt\SDL\Compiler\Dependency;

/**
 * Class ObjectDefinitionRecord
 */
class ObjectDefinitionRecord extends TypeDefinitionRecord
{
    public function getDependencies(): iterable
    {
        $invoke = $this->getAst()->find('#Implements', 0);

        if ($invoke) {
            foreach ($invoke->getChildren() as $child) {
                yield Dependency::fromAst($child, $this->getContext());
            }
        }
    }
}
