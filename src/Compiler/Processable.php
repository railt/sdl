<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Compiler\System\SystemInterface;

/**
 * Interface Processable
 */
interface Processable
{
    /**
     * @param SystemInterface $system
     */
    public function before(SystemInterface $system): void;

    /**
     * @param SystemInterface $system
     */
    public function after(SystemInterface $system): void;
}
