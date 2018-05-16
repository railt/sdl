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
     * Contains delimiter character for namespace.
     */
    public const NAMESPACE_DELIMITER = '/';

    /**
     * @return ContextInterface
     */
    public function current(): self;

    /**
     * @return CallStackInterface
     */
    public function getStack(): CallStackInterface;

    /**
     * @return ProvidesTypes
     */
    public function getTypes(): ProvidesTypes;

    /**
     * @return bool
     */
    public function atRoot(): bool;

    /**
     * @param string|null $name
     * @param Readable|null $file
     * @return LocalContextInterface
     */
    public function create(string $name = null, Readable $file = null): LocalContextInterface;
}
