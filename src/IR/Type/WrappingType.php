<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Type;

use Railt\SDL\IR\Type;

/**
 * Class WrappingType
 */
abstract class WrappingType extends Type
{
    /**
     * WrappingType constructor.
     * @param $name
     * @param null|TypeInterface $of
     */
    public function __construct($name, ?TypeInterface $of = null)
    {
        parent::__construct($name, $of);

        $this->getName()->lock();
    }

    /**
     * @return bool
     */
    public function isBuiltin(): bool
    {
        return $this->of->isBuiltin();
    }
}
