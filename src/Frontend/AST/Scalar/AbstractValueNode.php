<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\AST\Scalar;

use Railt\Parser\Ast\Rule;

/**
 * Class AbstractValue
 */
abstract class AbstractValueNode extends Rule implements ScalarInterface
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
}
