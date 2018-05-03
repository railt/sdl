<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Definition\Input;

use Railt\SDL\Reflection\Definition\Common\Dependent;
use Railt\SDL\Reflection\Definition\Common\HasDefaultValue;
use Railt\SDL\Reflection\Definition\Common\HasTypeIndication;
use Railt\SDL\Reflection\Definition\InputDefinition;
use Railt\SDL\Reflection\Definition\TypeDefinition;

/**
 * Interface InputFieldDefinition
 */
interface InputFieldDefinition extends Dependent, TypeDefinition, HasDefaultValue, HasTypeIndication
{
    /**
     * @return InputDefinition
     */
    public function getParent(): InputDefinition;
}
