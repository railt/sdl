<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Support;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Frontend\AST\Common\TypeNameNode;
use Railt\SDL\Frontend\IR\Value\ConstantValue;
use Railt\SDL\Frontend\IR\Value\NullValue;
use Railt\SDL\Frontend\IR\Value\ValueInterface;

/**
 * Trait TypeNameProvider
 */
trait TypeNameProvider
{
    /**
     * @return null|TypeNameNode|NodeInterface
     */
    public function getTypeNameNode(): ?TypeNameNode
    {
        return $this->first('TypeName', 1);
    }

    /**
     * @return null|string
     */
    public function getFullName(): ?string
    {
        $name = $this->getTypeNameNode();

        if ($name instanceof TypeNameNode) {
            return $name->getFullName();
        }

        return null;
    }

    /**
     * @return ValueInterface|null
     */
    protected function getFullNameValue(): ?ValueInterface
    {
        $name = $this->getTypeNameNode();

        if ($name instanceof RuleInterface) {
            return new ConstantValue($this->getFullName(), $name->getOffset());
        }

        return new NullValue($this->getOffset());
    }
}
