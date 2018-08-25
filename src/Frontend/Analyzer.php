<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Railt\SDL\Frontend\Analyzer\AnalyzerInterface;
use Railt\SDL\Frontend\Analyzer\LoggerAnalyzer;
use Railt\SDL\Frontend\Analyzer\PrioritizeAnalyzer;

/**
 * Class Analyzer
 */
class Analyzer implements AnalyzerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var AnalyzerInterface[]
     */
    private const DEFAULTS = [
        // Change opcodes priorities
        PrioritizeAnalyzer::class,

        // Should be last
        LoggerAnalyzer::class,
    ];

    /**
     * @var array|AnalyzerInterface[]
     */
    private $instances = [];

    /**
     * Analyzer constructor.
     */
    public function __construct()
    {
        $this->bootDefaults();
    }

    /**
     * @return void
     */
    private function bootDefaults(): void
    {
        foreach (self::DEFAULTS as $analyzer) {
            $this->addAnalyzer(new $analyzer);
        }
    }

    /**
     * @param AnalyzerInterface $analyzer
     * @return Analyzer
     */
    public function addAnalyzer(AnalyzerInterface $analyzer): Analyzer
    {
        $this->instances[] = $analyzer;

        if ($this->logger && $analyzer instanceof LoggerAwareInterface) {
            $analyzer->setLogger($this->logger);
        }

        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return Analyzer
     */
    public function setLogger(LoggerInterface $logger): Analyzer
    {
        $this->logger = $logger;

        foreach ($this->instances as $instance) {
            if ($instance instanceof LoggerAwareInterface) {
                $instance->setLogger($logger);
            }
        }

        return $this;
    }

    /**
     * @param iterable $opcodes
     * @return iterable
     */
    public function analyze(iterable $opcodes): iterable
    {
        foreach ($this->instances as $instance) {
            $opcodes = $instance->analyze($opcodes);
        }

        yield from $opcodes;
    }
}
