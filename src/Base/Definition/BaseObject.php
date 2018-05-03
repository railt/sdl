<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base;

use Railt\SDL\Base\Definition\BaseTypeDefinition;
use Railt\SDL\Reflection\Definition\ObjectDefinition;
use Railt\SDL\Reflection\Type;

/**
 * Class BaseObject
 */
class BaseObject extends BaseTypeDefinition implements ObjectDefinition
{
    /**
     * @return Type
     */
    public function getType(): Type
    {
        return new Type\ObjectType();
    }
}
