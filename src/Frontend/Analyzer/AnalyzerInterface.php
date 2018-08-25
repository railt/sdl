<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend\Analyzer;

/**
 * Interface AnalyzerInterface
 */
interface AnalyzerInterface
{
    /**
     * @param iterable $opcodes
     * @return iterable
     */
    public function analyze(iterable $opcodes): iterable;
}
