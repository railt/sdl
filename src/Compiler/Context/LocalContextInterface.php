<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Context;

use Railt\Io\Readable;
use Railt\SDL\Compiler\Component\TypeName;

/**
 * Interface LocalContextInterface
 */
interface LocalContextInterface extends ContextInterface
{
    /**
     * @return ContextInterface
     */
    public function previous(): ContextInterface;

    /**
     * @return GlobalContextInterface
     */
    public function global(): GlobalContextInterface;

    /**
     * @return Readable
     */
    public function getFile(): Readable;

    /**
     * @return TypeName
     */
    public function getName(): TypeName;
}
