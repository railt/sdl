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
use Railt\Parser\Ast\RuleInterface;

/**
 * Class Value
 */
abstract class Value implements ValueInterface
{
    /**
     * @var NodeInterface
     */
    private $rule;

    /**
     * @var mixed
     */
    private $value;

    /**
     * StringValue constructor.
     * @param NodeInterface $rule
     */
    public function __construct(NodeInterface $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @param NodeInterface $rule
     * @return bool
     */
    public static function match(NodeInterface $rule): bool
    {
        return $rule->getName() === static::getAstName();
    }

    /**
     * @return string
     */
    abstract protected static function getAstName(): string;

    /**
     * @param NodeInterface $rule
     * @return mixed
     */
    abstract protected function parse(NodeInterface $rule);

    /**
     * @return mixed
     */
    public function toScalar()
    {
        if ($this->value === null) {
            $this->value = $this->parse($this->rule);
        }

        return $this->value;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->rule->getOffset();
    }
}
