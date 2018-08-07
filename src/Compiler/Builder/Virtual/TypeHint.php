<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder\Virtual;

use Railt\Reflection\AbstractDefinition;
use Railt\Reflection\Contracts\Definition\Behaviour\ProvidesTypeIndication;
use Railt\Reflection\Definition\Behaviour\HasTypeIndication;

/**
 * Class TypeHint
 */
abstract class TypeHint extends AbstractDefinition implements ProvidesTypeIndication
{
    use HasTypeIndication;
}
