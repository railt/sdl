<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Type;

/**
 * Class AnyType
 */
class AnyType extends BaseType
{
    /**
     * AnyType constructor.
     */
    public function __construct()
    {
        parent::__construct('Any', $this);
    }
}
