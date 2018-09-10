<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\IR;

use Railt\Io\PositionInterface;
use Railt\Io\Readable;

/**
 * Interface DefinitionInterface
 */
interface DefinitionInterface extends PositionInterface
{
    /**
     * @return Readable
     */
    public function getFile(): Readable;
}
