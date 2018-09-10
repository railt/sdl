<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Invocation;

use Railt\Parser\Ast\Rule;

/**
 * Class DescriptionValue
 */
class DescriptionValue extends Rule implements AstValueInterface
{
    /**
     * @return string
     */
    public function toPrimitive(): string
    {
        return $this->getChild(0)->toPrimitive();
    }
}
