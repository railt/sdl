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
use Railt\Parser\Ast\Rule;
use Railt\SDL\IR\Value;
use Railt\SDL\IR\ValueInterface;

/**
 * Class AbstractValue
 */
abstract class AbstractValueNode extends Rule implements AstValueInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @return mixed
     */
    abstract protected function parse();

    /**
     * @return mixed
     */
    public function toPrimitive()
    {
        if ($this->value === null) {
            $this->value = $this->parse();
        }

        return $this->value;
    }

    /**
     * @param Readable $file
     * @return ValueInterface
     */
    public function toValue(Readable $file): ValueInterface
    {
        return (new Value($this->toPrimitive()))->in($file, $this->getOffset());
    }
}
