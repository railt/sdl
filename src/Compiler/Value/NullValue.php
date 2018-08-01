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
use Railt\Parser\Ast\RuleInterface;

/**
 * Class BooleanValue
 */
class BooleanValue extends Value
{
    /**
     * @return string
     */
    protected static function getAstName(): string
    {
        return 'Boolean';
    }

    /**
     * @param NodeInterface|RuleInterface $rule
     * @return bool
     */
    protected function parse(NodeInterface $rule): bool
    {
        $value = $rule->getChild(0)->getValue();

        return $value === 'true';
    }
}
