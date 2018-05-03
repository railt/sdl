<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\SDL\Reflection\Definition\TypeDefinition;

/**
 * Interface Dictionary
 */
interface Dictionary
{
    /**
     * @param string $type
     * @return mixed
     */
    public function get(string $type): TypeDefinition;

    /**
     * @param string $type
     * @return bool
     */
    public function has(string $type): bool;

    /**
     * @param TypeDefinition $type
     */
    public function register(TypeDefinition $type): void;
}
