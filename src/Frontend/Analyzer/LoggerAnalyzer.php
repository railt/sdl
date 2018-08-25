<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Analyzer;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Railt\SDL\Frontend\IR\OpcodeInterface;

/**
 * Class LoggerAnalyzer
 */
class LoggerAnalyzer implements LoggerAwareInterface, AnalyzerInterface
{
    use LoggerAwareTrait;

    /**
     * @param iterable $opcodes
     * @return iterable|OpcodeInterface[]
     */
    public function analyze(iterable $opcodes): iterable
    {
        foreach ($opcodes as $opcode) {
            if ($this->logger) {
                $this->logger->debug($opcode);
            }

            yield $opcode;
        }
    }
}
