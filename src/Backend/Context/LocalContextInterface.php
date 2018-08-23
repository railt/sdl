<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document;

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
     * @return Document
     */
    public function getDocument(): Document;

    /**
     * @return ContextInterface
     */
    public function getParent(): ContextInterface;
}
