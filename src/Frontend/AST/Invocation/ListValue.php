<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Invocation;

/**
 * Class ListValue
 */
class ListValue extends Value
{
    /**
     * @return array|mixed
     */
    protected function parse()
    {
        $result = [];

        /** @var AstValueInterface $child */
        foreach ($this->getChildren() as $child) {
            $result[] = $child->toPrimitive();
        }

        return $result;
    }
}
