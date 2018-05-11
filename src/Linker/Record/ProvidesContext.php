<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Record;

/**
 * Interface ProvidesContext
 */
interface ProvidesContext
{
    /**
     * @return string
     */
    public function getContext(): string;

    /**
     * @return bool
     */
    public function atRoot(): bool;

    /**
     * @return bool
     */
    public function shouldRollback(): bool;
}
