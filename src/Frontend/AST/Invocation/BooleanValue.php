<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Invocation;

use Railt\Parser\Ast\LeafInterface;

/**
 * Class BooleanValue
 */
class BooleanValue extends Value
{
    /**
     * @return bool|mixed
     */
    protected function parse()
    {
        /** @var LeafInterface $leaf */
        $leaf = $this->getChild(0);

        return $leaf->getValue() === 'true';
    }
}
