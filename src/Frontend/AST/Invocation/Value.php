<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Invocation;

use Railt\Parser\Ast\Rule;

/**
 * Class Value
 */
abstract class Value extends Rule implements AstValueInterface
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
