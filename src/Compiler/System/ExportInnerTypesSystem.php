<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\SDL\Compiler\Component\InnerDefinitionsComponent;
use Railt\SDL\Compiler\PipelineInterface;
use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Class ExportInnerTypesSystem
 */
class ExportInnerTypesSystem extends System
{
    /**
     * @var PipelineInterface
     */
    private $pipeline;

    /**
     * ExportInnerTypesSystem constructor.
     * @param PipelineInterface $pipeline
     */
    public function __construct(PipelineInterface $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * @param RecordInterface $record
     */
    public function provide(RecordInterface $record): void
    {
        $this->when($record)
            ->contains(InnerDefinitionsComponent::class)
            ->then(function (InnerDefinitionsComponent $provider) use ($record): void {
                foreach ($provider->getDefinitions() as $ast) {
                    $this->pipeline->insertAst($record->getContext()->getFile(), $ast);
                }
            });
    }
}
