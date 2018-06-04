<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Common;

use Railt\SDL\Compiler\Context\LocalContextInterface;

/**
 * Interface ProvidesContext
 */
interface ProvidesContext
{
    /**
     * @return LocalContextInterface
     */
    public function getContext(): LocalContextInterface;
}
