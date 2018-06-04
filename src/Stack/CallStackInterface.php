<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Stack;
use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Position;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Interface CallStackInterface
 */
interface CallStackInterface extends \Countable, \IteratorAggregate
{
    /**
     * @return Item
     */
    public function pop(): Item;

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     * @return CallStackInterface
     */
    public function pushAst(Readable $file, RuleInterface $ast): CallStackInterface;

    /**
     * @param Readable $file
     * @param Position $position
     * @param string|\Closure $message
     * @return CallStackInterface
     */
    public function push(Readable $file, Position $position, $message): CallStackInterface;

    /**
     * @param RecordInterface $record
     * @return CallStackInterface
     */
    public function pushRecord(RecordInterface $record): CallStackInterface;
}
