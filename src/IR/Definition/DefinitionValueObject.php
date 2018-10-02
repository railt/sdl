<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR\Definition;

use Railt\Io\Readable;
use Railt\SDL\IR\ValueObject;

/**
 * @property Readable $file
 * @property int $offset
 */
abstract class DefinitionValueObject extends ValueObject
{
}
