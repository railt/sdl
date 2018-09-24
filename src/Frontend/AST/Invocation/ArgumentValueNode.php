<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Invocation;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Definition\Provider\DependentNameProvider;
use Railt\SDL\IR\ValueInterface;

/**
 * Class ArgumentValue
 */
class ArgumentValueNode extends AbstractValueNode
{
    use DependentNameProvider;

    /**
     * @return \Generator|mixed
     */
    protected function parse()
    {
        yield $this->getFullName() => $this->getValue()->toPrimitive();
    }

    /**
     * @return null|RuleInterface|AstValueInterface
     */
    public function getValue()
    {
        /** @var RuleInterface|AstValueInterface $child */
        foreach ($this->getChildren() as $child) {
            if (\in_array($child->getName(), static::VALUE_NODE_NAMES, true)) {
                return $child;
            }
        }

        return null;
    }

    /**
     * @param Readable $file
     * @return ValueInterface
     */
    public function toValue(Readable $file): ValueInterface
    {
        return $this->getValue()->toValue($file);
    }
}
