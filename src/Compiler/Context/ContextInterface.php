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
use Railt\SDL\Stack\CallStackInterface;

/**
 * Interface ContextInterface
 */
interface ContextInterface
{
    /**
     * @return Readable
     */
    public function getFile(): Readable;

    /**
     * @return CallStackInterface
     */
    public function getStack(): CallStackInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return ContextInterface
     */
    public function resolve(string $name): self;

    /**
     * @return null|ContextInterface
     */
    public function previous(): ?self;

    /**
     * @return bool
     */
    public function isGlobal(): bool;
}
