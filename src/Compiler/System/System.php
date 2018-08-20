<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definition\TypeDefinition;
use Railt\SDL\Compiler\Builder;
use Railt\SDL\Compiler\Process\DeferredInterface;
use Railt\SDL\Compiler\Process\Pipeline;
use Railt\SDL\Compiler\System\Support\DeferredPriorities;
use Railt\SDL\Exception\TypeConflictException;

/**
 * Class System
 */
abstract class System implements SystemInterface
{
    use DeferredPriorities;

    /**
     * @var Builder
     */
    protected $process;

    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * System constructor.
     * @param Builder $process
     * @param Pipeline $pipeline
     */
    public function __construct(Builder $process, Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
        $this->process = $process;
    }

    /**
     * @param int $priority
     * @return DeferredInterface
     */
    protected function when(int $priority): DeferredInterface
    {
        return $this->pipeline->on($priority);
    }

    /**
     * @param string $type
     * @param Definition|TypeDefinition $from
     * @return TypeDefinition
     * @throws \Railt\Reflection\Exception\TypeNotFoundException
     */
    protected function get(string $type, Definition $from): TypeDefinition
    {
        return $from->getDictionary()->get($type, $from);
    }

    /**
     * @param Definition $definition
     * @return TypeConflictException
     */
    protected function redeclareException(Definition $definition): TypeConflictException
    {
        $error = \sprintf('Can no redeclare already registered %s', $definition);

        $exception = new TypeConflictException($error);
        $exception->in($definition);

        return $exception;
    }
}
