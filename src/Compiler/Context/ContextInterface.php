<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Context;

use Railt\SDL\Stack\CallStack;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Interface ContextInterface
 */
interface ContextInterface extends ContextStackInterface
{
    /**
     * @return CallStackInterface|CallStack
     */
    public function getCallStack(): CallStackInterface;
}
