<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Record;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Position;
use Railt\Io\Readable;

/**
 * Interface RecordInterface
 */
interface RecordInterface
{
    /**
     * @return Readable
     */
    public function getFile(): Readable;

    /**
     * @return RuleInterface
     */
    public function getAst(): RuleInterface;

    /**
     * @return Position
     */
    public function getPosition(): Position;
}
