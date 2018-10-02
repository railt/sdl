<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Context;

use Railt\Io\Readable;
use Railt\SDL\IR\Type\TypeNameInterface;

/**
 * Interface LocalContextInterface
 */
interface ContextInterface extends ContextVariablesInterface, ContextInheritanceInterface
{
    /**
     * @return TypeNameInterface
     */
    public function getName(): TypeNameInterface;

    /**
     * @return Readable
     */
    public function getFile(): Readable;

    /**
     * @param TypeNameInterface $name
     * @return ContextInterface
     */
    public function create(TypeNameInterface $name): ContextInterface;

    /**
     * @return ContextInterface
     */
    public function close(): ContextInterface;
}
