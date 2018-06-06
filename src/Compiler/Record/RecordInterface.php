<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Record;

use Railt\Io\Readable;
use Railt\SDL\Compiler\Context\ProvidesContext;
use Railt\SDL\Heap\PriorityInterface;

/**
 * Interface RecordInterface
 */
interface RecordInterface extends ProvidesContext, ProvidesPosition, PriorityInterface, ProvidesAbstractSyntaxTree
{
    /**
     * @return Readable
     */
    public function getFile(): Readable;
}
