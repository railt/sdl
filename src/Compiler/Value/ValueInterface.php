<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Value;

use Railt\Parser\Ast\NodeInterface;

/**
 * Interface ValueInterface
 */
interface ValueInterface
{
    /**
     * @param NodeInterface $rule
     * @return bool
     */
    public static function match(NodeInterface $rule): bool;

    /**
     * @return mixed
     */
    public function toScalar();

    /**
     * @return int
     */
    public function getOffset(): int;
}
