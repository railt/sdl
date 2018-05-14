<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Compiler\Record\ProvidesName;
use Railt\SDL\Compiler\Record\ProvidesPriority;
use Railt\SDL\Compiler\Record\RecordInterface;
use Railt\SDL\Exception\TypeNotFoundException;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Container
 */
class Container implements ProvidesTypes
{
    /**
     * @var RecordInterface[]|\SplPriorityQueue
     */
    private $records;

    /**
     * @var array|RecordInterface[]
     */
    private $definitions = [];

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * Container constructor.
     * @param CallStackInterface $stack
     */
    public function __construct(CallStackInterface $stack)
    {
        $this->records = new \SplPriorityQueue();
        $this->stack   = $stack;
    }

    /**
     * @param RecordInterface $record
     */
    public function push(RecordInterface $record): void
    {
        if ($record instanceof ProvidesName) {
            $this->definitions[$record->getName()] = $record;
        }

        $this->records->insert($record, $this->priority($record));
    }

    /**
     * @param RecordInterface $record
     * @return int
     */
    private function priority(RecordInterface $record): int
    {
        if ($record instanceof ProvidesPriority) {
            return $record->getPriority();
        }

        return 0;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function has(string $type): bool
    {
        return \array_key_exists($type, $this->definitions);
    }

    /**
     * @param string $type
     * @return RecordInterface
     * @throws TypeNotFoundException
     */
    public function get(string $type): RecordInterface
    {
        if (\array_key_exists($type, $this->definitions)) {
            return $this->definitions[$type];
        }

        throw new TypeNotFoundException(\sprintf('Type "%s" not found', $type), $this->stack);
    }

    /**
     * @return \Traversable|RecordInterface[]
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->records as $record) {
            yield $record;
        }
    }
}
