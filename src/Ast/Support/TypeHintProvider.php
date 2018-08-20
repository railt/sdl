<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Support;

use Railt\SDL\Ast\Common\TypeHintNode;

/**
 * Trait TypeHintProvider
 */
trait TypeHintProvider
{
    /**
     * @return TypeHintNode
     */
    public function getTypeHintNode(): TypeHintNode
    {
        return $this->first('TypeHint', 1);
    }
}
