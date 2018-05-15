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

/**
 * Interface LocalContextInterface
 */
interface LocalContextInterface extends ContextInterface
{
    /**
     * @return Readable
     */
    public function getFile(): Readable;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return ContextInterface|LocalContextInterface|GlobalContextInterface
     */
    public function previous(): ContextInterface;

    /**
     * @return GlobalContextInterface
     */
    public function global(): GlobalContextInterface;
}
