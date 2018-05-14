<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Interface ProvidesTypes
 */
interface ProvidesTypes extends \IteratorAggregate
{
    /**
     * @param string $type
     * @return bool
     */
    public function has(string $type): bool;

    /**
     * @param string $type
     * @return RecordInterface
     */
    public function get(string $type): RecordInterface;

    /**
     * @param RecordInterface $record
     */
    public function push(RecordInterface $record): void;
}
