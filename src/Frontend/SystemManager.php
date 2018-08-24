<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\SDL\Compiler\Process\DeferredInterface;
use Railt\SDL\Compiler\Process\Emittable;
use Railt\SDL\Compiler\Process\Pipeline;
use Railt\SDL\Frontend\IR\Prototype;
use Railt\SDL\Frontend\System\DefinitionSystem;
use Railt\SDL\Frontend\System\SystemInterface;

/**
 * Class SystemManager
 */
class SystemManager
{
    /**
     * @var string[]
     */
    private const DEFAULT_SYSTEMS = [
        DefinitionSystem::class,
    ];

    /**
     * @var array|SystemInterface[]
     */
    private $systems = [];

    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * SystemManager constructor.
     */
    public function __construct()
    {
        $this->pipeline = new Pipeline();

        $this->bootDefaultSystems();
    }

    /**
     * @return void
     */
    private function bootDefaultSystems(): void
    {
        foreach (self::DEFAULT_SYSTEMS as $system) {
            $this->systems[] = new $system($this);
        }
    }

    /**
     * @param SystemInterface $system
     * @return SystemManager
     */
    public function addSystem(SystemInterface $system): self
    {
        $this->systems[] = $system;

        return $this;
    }

    /**
     * @param Readable $readable
     * @param RuleInterface $ast
     * @return \Generator
     */
    public function run(Readable $readable, RuleInterface $ast): \Generator
    {
        foreach ($ast as $child) {
            yield from $this->extract($readable, $this->apply($readable, $child));
            yield from $this->extract($readable, $this->runDeferred());
        }
    }

    /**
     * @return \Generator
     */
    private function runDeferred(): \Generator
    {
        /** @var Emittable $deferred */
        foreach ($this->pipeline as $deferred) {
            $result = $deferred->emit();

            if (\is_iterable($result)) {
                yield from $result;
            } else {
                yield $result;
            }
        }
    }

    /**
     * Applies additional result analyse.
     *
     * @param Readable $readable
     * @param \Generator $result
     * @return \Generator
     */
    private function extract(Readable $readable, \Generator $result): \Generator
    {
        while ($result->valid()) {
            [$key, $value] = [$result->key(), $result->current()];

            switch (true) {
                case \is_callable($value):
                    $result->send($this->deferred((int)$key, $value));
                    break;

                case $value instanceof Prototype:
                    yield $key => $value->create($readable, 0);
                    $result->next();
                    break;

                default:
                    yield $key => $value;
                    $result->next();
            }
        }
    }

    /**
     * @param int $priority
     * @param callable $value
     * @return Emittable|DeferredInterface
     */
    private function deferred(int $priority, callable $value): Emittable
    {
        $callback = $value instanceof \Closure ? $value : \Closure::fromCallable($value);

        return $this->pipeline->on($priority)->then($callback);
    }

    /**
     * Applies systems to all AST nodes.
     *
     * @param Readable $readable
     * @param RuleInterface $ast
     * @return \Generator
     */
    private function apply(Readable $readable, RuleInterface $ast): \Generator
    {
        foreach ($this->systems as $system) {
            if ($system->match($ast)) {
                $result = $system->apply($readable, $ast);

                if ($result instanceof \Generator) {
                    yield from $result;
                }
            }
        }
    }
}
