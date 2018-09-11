<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Definition\Provider;

use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Invocation\AstValueInterface;

/**
 * Class DefaultValueProvider
 */
trait DefaultValueProvider
{
    /**
     * @return AstValueInterface|null
     */
    public function getDefaultValue(): ?AstValueInterface
    {
        $values = AstValueInterface::VALUE_NODE_NAMES;

        /** @var RuleInterface|AstValueInterface $child */
        foreach ($this->getChildren() as $child) {
            if (\in_array($child->getName(), $values, true)) {
                return $child;
            }
        }

        return null;
    }
}
