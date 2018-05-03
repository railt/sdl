<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Type\Object;

use Railt\SDL\Reflection\Type\BaseType;

/**
 * Class FieldType
 */
class FieldType extends BaseType
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Field';
    }
}
