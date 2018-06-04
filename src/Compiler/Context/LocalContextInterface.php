<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Context;

use Railt\SDL\Compiler\Common\ProvidesFile;

/**
 * Interface LocalContextInterface
 */
interface LocalContextInterface extends ContextInterface, ProvidesFile
{
    /**
     * @return ContextInterface
     */
    public function previous(): ContextInterface;

    /**
     * @return GlobalContextInterface
     */
    public function global(): GlobalContextInterface;
}
