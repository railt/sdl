<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Record;

/**
 * Class Store
 */
class Store
{
    /**
     * @var array|RecordInterface[]
     */
    private $records = [];

    /**
     * @param RecordInterface $record
     */
    public function add(RecordInterface $record): void
    {
        $this->records[] = $record;
    }
}
