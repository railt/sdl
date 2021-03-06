<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\TypeHint;

use Railt\GraphQL\AST\Common\Name;
use Railt\GraphQL\AST\Node;

/**
 * Class Type
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
final class Type extends Node implements TypeHintInterface
{
    /**
     * @var string
     */
    public $type;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof Name) {
            $this->type = $value->value;

            return true;
        }

        return parent::each($value);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return TypeHintInterface
     */
    public function of(): TypeHintInterface
    {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->type;
    }
}
