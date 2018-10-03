<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Definition;

use Railt\SDL\IR\Type;

/**
 * @property string $name
 * @property string $description
 * @property Type $type
 */
abstract class TypeDefinitionValueObject extends DefinitionValueObject
{
}
