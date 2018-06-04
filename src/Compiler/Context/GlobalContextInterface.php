<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Context;

/**
 * Interface GlobalContextInterface
 */
interface GlobalContextInterface extends ContextInterface, \IteratorAggregate
{
    /**
     * @param string $name
     * @return ContextInterface|null
     */
    public function get(string $name): ?ContextInterface;
}
