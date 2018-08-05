<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Processor;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler\Factory;
use Railt\SDL\Compiler\Pipeline;

/**
 * Class DefinitionProcessor
 */
abstract class DefinitionProcessor implements ProcessorInterface
{
    /**
     * @var Document|\Railt\Reflection\Document
     */
    protected $document;

    /**
     * @var Pipeline
     */
    protected $pipeline;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * DefinitionProcessor constructor.
     * @param Document $document
     * @param Pipeline $pipeline
     * @param Factory $factory
     */
    public function __construct(Document $document, Pipeline $pipeline, Factory $factory)
    {
        $this->document = $document;
        $this->pipeline = $pipeline;
        $this->factory = $factory;
    }

    /**
     * @param RuleInterface $rule
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    protected function build(RuleInterface $rule): Definition
    {
        return $this->factory->build($rule);
    }

    /**
     * @param \Closure $then
     * @return DefinitionProcessor
     */
    protected function deferred(\Closure $then): DefinitionProcessor
    {
        $this->pipeline->push(1, $then);

        return $this;
    }

    /**
     * @param \Closure $then
     * @return DefinitionProcessor
     */
    protected function then(\Closure $then): DefinitionProcessor
    {
        $this->pipeline->push(2, $then);

        return $this;
    }
}
