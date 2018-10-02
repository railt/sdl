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
 * Interface DefinitionInterface
 */
interface DefinitionInterface
{
    /**
     * @param string $name
     * @param mixed|DefinitionInterface $value
     */
    public function set(string $name, $value): void;

    /**
     * @param string $name
     * @return mixed|DefinitionInterface
     */
    public function get(string $name);
}
