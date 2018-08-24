<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST;

use Railt\SDL\Frontend\Context;
use Railt\SDL\Frontend\IR\OpcodeInterface;

/**
 * Interface ProvidesOpcode
 */
interface ProvidesOpcode
{
    /**
     * @param Context $context
     * @return iterable|OpcodeInterface[]|\Generator
     */
    public function getOpcodes(Context $context): iterable;
}
