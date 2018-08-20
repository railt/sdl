<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Railt\Parser\Ast\RuleInterface;
use Railt\Reflection\Contracts\Definition;
use Railt\SDL\Compiler\Process\Pipeline;
use Railt\SDL\Compiler\System;
use Railt\SDL\Compiler\System\SystemInterface;

/**
 * Class SystemManager
 */
class SystemManager implements SystemInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var SystemInterface[]
     */
    private const DEFAULT_SYSTEMS = [
        System\OffsetSystem::class,
        System\TypeHintSystem::class,
        System\DescriptionSystem::class,
        System\ImplementationSystem::class,

        System\Provider\FieldSystem::class,
        System\Provider\ArgumentSystem::class,
        System\Provider\DocumentSystem::class,
        System\Provider\DirectivesSystem::class,
        System\Provider\ArgumentInvocationSystem::class,
    ];

    /**
     * @var array|SystemInterface[]
     */
    private $systems = [];

    /**
     * SystemManager constructor.
     * @param Builder $process
     * @param Pipeline $pipeline
     */
    public function __construct(Builder $process, Pipeline $pipeline)
    {
        $this->boot($process, $pipeline);
    }

    /**
     * @param Builder $process
     * @param Pipeline $pipeline
     * @return void
     */
    private function boot(Builder $process, Pipeline $pipeline): void
    {
        foreach (self::DEFAULT_SYSTEMS as $system) {
            $this->systems[] = new $system($process, $pipeline);
        }
    }

    /**
     * @param Definition $def
     * @param RuleInterface $ast
     */
    public function resolve(Definition $def, RuleInterface $ast): void
    {
        foreach ($this->systems as $system) {
            if ($this->logger) {
                $message = 'Apply system %s for %s (%s:%d)';
                $this->logger->debug(\sprintf($message, \get_class($system),
                    $def, $def->getFile()->getPathname(), $def->getLine()));
            }

            $system->resolve($def, $ast);
        }
    }
}
