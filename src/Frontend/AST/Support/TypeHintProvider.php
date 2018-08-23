<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Support;

use Railt\Parser\Ast\NodeInterface;
use Railt\SDL\Frontend\AST\Common\TypeHintNode;

/**
 * Trait TypeHintProvider
 */
trait TypeHintProvider
{
    /**
     * @return TypeHintNode|NodeInterface|null
     */
    public function getTypeHintNode(): ?TypeHintNode
    {
        return $this->first('TypeHint', 1);
    }
}
