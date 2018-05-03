<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Defintions\Object;

use Railt\SDL\Reflection\Definition\Common\Dependent;
use Railt\SDL\Reflection\Definition\Common\HasTypeIndication;
use Railt\SDL\Reflection\Definition\InterfaceDefinition;
use Railt\SDL\Reflection\Definition\ObjectDefinition;
use Railt\SDL\Reflection\Definition\TypeDefinition;

/**
 * Interface FieldDefinition
 */
interface FieldDefinition extends Dependent, TypeDefinition, HasArguments, HasTypeIndication
{
    /**
     * @return ObjectDefinition|InterfaceDefinition
     */
    public function getParent(): InterfaceDefinition;
}
