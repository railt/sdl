<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection;

use Railt\Io\Position;
use Railt\Io\Readable;

/**
 * Interface Definition
 */
interface Definition
{
    /**
     * @return Document
     */
    public function getDocument(): Document;

    /**
     * @return Position
     */
    public function getDeclarationInfo(): Position;

    /**
     * @return Readable
     */
    public function getFile(): Readable;

    /**
     * @return string
     */
    public function __toString(): string;
}
