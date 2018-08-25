<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Analyzer;

use Railt\SDL\Frontend\IR\OpcodeInterface;

/**
 * Class PrioritizeAnalyzer
 */
class PrioritizeAnalyzer implements AnalyzerInterface
{
    /**
     * @var int[]
     */
    protected const PRIORITIES = [
        OpcodeInterface::RL_OPEN   => -1,
        OpcodeInterface::RL_DEFINE => -1,
    ];

    /**
     * @var array
     */
    private $priorities = [];

    /**
     * @param iterable|OpcodeInterface[] $opcodes
     * @return iterable|OpcodeInterface[]
     */
    public function analyze(iterable $opcodes): iterable
    {
        foreach ($opcodes as $opcode) {
            $priority = self::PRIORITIES[$opcode->getOperation()] ?? 0;

            $this->priority($priority)->push($opcode);
        }

        foreach ($this->priorities as $queue) {
            yield from $queue;
        }
    }

    /**
     * @param int $index
     * @return \SplQueue
     */
    private function priority(int $index): \SplQueue
    {
        $queue = $this->priorities[$index] ?? null;

        if (! $queue) {
            $this->priorities[$index] = new \SplQueue();
        }

        return $this->priorities[$index];
    }
}
