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
 * Interface GlobalContextInterface
 */
interface GlobalContextInterface extends ContextInterface
{
    /**
     * @return LocalContextInterface
     */
    public function pop(): LocalContextInterface;

    /**
     * @param LocalContextInterface $context
     * @return LocalContextInterface
     */
    public function push(LocalContextInterface $context): LocalContextInterface;

    /**
     * @return bool
     */
    public function isEmpty(): bool;
}
