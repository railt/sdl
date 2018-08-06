<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Builder;

use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\SDL\Compiler\Factory;
use Railt\SDL\Compiler\Pipeline;

/**
 * Class Builder
 */
abstract class Builder implements BuilderInterface
{
    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * Builder constructor.
     * @param Pipeline $pipeline
     * @param Factory $factory
     */
    public function __construct(Pipeline $pipeline, Factory $factory)
    {
        $this->pipeline = $pipeline;
        $this->factory = $factory;
    }

    /**
     * @param \Closure $then
     * @return Builder
     */
    protected function future(\Closure $then): Builder
    {
        $this->pipeline->push(3, $then);

        return $this;
    }

    /**
     * @param \Closure $then
     * @return Builder
     */
    protected function deferred(\Closure $then): Builder
    {
        $this->pipeline->push(2, $then);

        return $this;
    }

    /**
     * @param RuleInterface $ast
     * @param Definition $parent
     * @return Definition
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    protected function dependent(RuleInterface $ast, Definition $parent): Definition
    {
        return $this->factory->build($ast, $parent);
    }
}
