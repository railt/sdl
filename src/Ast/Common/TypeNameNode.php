<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Common;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\Rule;

/**
 * Class TypeNameNode
 */
class TypeNameNode extends Rule
{
    /**
     * @return string
     */
    public function getFullName(): string
    {
        /** @var LeafInterface $leaf */
        $leaf = $this->getChild(0);

        return $leaf->getValue();
    }
}
