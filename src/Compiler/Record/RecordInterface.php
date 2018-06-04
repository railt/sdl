<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Record;

use Railt\SDL\Heap\PriorityInterface;
use Railt\SDL\Compiler\Common\ProvidesFile;
use Railt\SDL\Compiler\Common\ProvidesContext;
use Railt\SDL\Compiler\Common\ProvidesPosition;
use Railt\SDL\Compiler\Common\ProvidesAbstractSyntaxTree;

/**
 * Interface RecordInterface
 */
interface RecordInterface extends
    ProvidesFile,
    ProvidesContext,
    ProvidesPosition,
    PriorityInterface,
    ProvidesAbstractSyntaxTree
{

}
