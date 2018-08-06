<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Reflection\Contracts\Definition\Behaviour\ProvidesTypeIndication;
use Railt\SDL\Compiler\Ast\Value\BaseValueNode;
use Railt\SDL\Compiler\Builder\Value\ValueInterface;
use Railt\SDL\Compiler\Coercion\ValueCoercion;

/**
 * Class Coercion
 */
class Coercion
{
    /**
     * @var ValueCoercion
     */
    private $value;

    /**
     * Coercion constructor.
     */
    public function __construct()
    {
        $this->value = new ValueCoercion();
    }

    /**
     * @param ProvidesTypeIndication $type
     * @param ValueInterface $value
     * @return mixed
     */
    public function value(ProvidesTypeIndication $type, ValueInterface $value = null)
    {
        return $this->value->apply($type, $value);
    }
}
