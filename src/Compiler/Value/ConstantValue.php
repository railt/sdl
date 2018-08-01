<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Value;

use Railt\Parser\Ast\LeafInterface;
use Railt\Parser\Ast\NodeInterface;

/**
 * Class EnumValue
 */
class EnumValue extends BaseValue
{
    /**
     * @param NodeInterface $rule
     * @return bool
     */
    public static function match(NodeInterface $rule): bool
    {
        return $rule instanceof LeafInterface;
    }

    /**
     * @return string
     * @throws \LogicException
     */
    protected static function getAstName(): string
    {
        throw new \LogicException(__METHOD__ . ' is non-allowed');
    }

    /**
     * @param NodeInterface|LeafInterface $rule
     * @return string
     */
    protected function parse(NodeInterface $rule): string
    {
        return (string)$rule->getValue();
    }
}
