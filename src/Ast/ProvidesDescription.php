<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast;

/**
 * Interface ProvidesDescription
 */
interface ProvidesDescription
{
    /**
     * @return null|string
     */
    public function getDescription(): ?string;
}
