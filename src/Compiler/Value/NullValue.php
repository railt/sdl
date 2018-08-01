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
 * Class NullValue
 */
class NullValue extends BaseValue
{
    /**
     * @return string
     */
    protected static function getAstName(): string
    {
        return 'Null';
    }

    /**
     * @param NodeInterface $rule
     */
    protected function parse(NodeInterface $rule): void
    {
    }
}
