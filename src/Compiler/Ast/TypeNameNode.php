<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Ast;

use Railt\Parser\Ast\Rule;

/**
 * Class TypeNameNode
 */
class TypeNameNode extends Rule
{
    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->getChild(0)->getValue();
    }
}
