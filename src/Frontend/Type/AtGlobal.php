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
 * Class AtGlobal
 */
class AtGlobal extends TypeName
{
    /**
     * @param TypeNameInterface $prefix
     * @return TypeNameInterface
     */
    public function in(TypeNameInterface $prefix): TypeNameInterface
    {
        return $this;
    }
}
