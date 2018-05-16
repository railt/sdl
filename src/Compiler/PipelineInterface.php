<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Context\ProvidesTypes;
use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Interface PipelineInterface
 */
interface PipelineInterface
{
    /**
     * @param Readable $file
     * @return ProvidesTypes
     */
    public function read(Readable $file): ProvidesTypes;

    /**
     * @param Readable $file
     * @param RuleInterface $ast
     */
    public function insertAst(Readable $file, RuleInterface $ast): void;

    /**
     * @param RecordInterface $record
     */
    public function insert(RecordInterface $record): void;
}
