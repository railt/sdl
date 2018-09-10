<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

/**
 * Interface ValueInterface
 */
interface ValueInterface extends DefinitionInterface
{
    /**
     * @return mixed|string|int|null|float|bool|array
     */
    public function getValue();
}
