<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition\Provider;

use Railt\SDL\Frontend\AST\Definition\Common\TypeHintNode;

/**
 * Trait ProvidesTypeHint
 */
trait ProvidesTypeHint
{
    /**
     * @return null|TypeHintNode
     */
    private function getTypeHint(): ?TypeHintNode
    {
        return $this->first('TypeHint', 1);
    }

    /**
     * @return int
     */
    public function getHintModifiers(): int
    {
        if ($hint = $this->getTypeHint()) {
            return $hint->getModifiers();
        }

        return 0;
    }

    /**
     * @return null|string
     */
    public function getHintTypeName(): ?string
    {
        if ($hint = $this->getTypeHint()) {
            return $hint->first('TypeName', 6)
                ->getChild(0)
                ->getValue();
        }

        return null;
    }
}
