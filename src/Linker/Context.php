<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker;

use Railt\SDL\Exception\LossOfStackException;
use Railt\SDL\Linker\Record\ProvidesContext;
use Railt\SDL\Linker\Record\ProvidesName;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Context
 */
class Context
{
    /**
     * @var \SplStack|string[]
     */
    private $context;

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * Context constructor.
     * @param CallStackInterface $stack
     */
    public function __construct(CallStackInterface $stack)
    {
        $this->context = new \SplStack();
        $this->stack   = $stack;
    }

    /**
     * @param ProvidesName $record
     * @return string
     */
    public function resolve(ProvidesName $record): string
    {
        $name = $record->isGlobal() ? $record->getName() : $this->mergeName($record);

        return $this->name($name);
    }

    /**
     * @param ProvidesName $record
     * @return string
     */
    private function mergeName(ProvidesName $record): string
    {
        return $this->merge($record->getName());
    }

    /**
     * @param string $name
     * @return string
     */
    private function merge(string $name): string
    {
        $name = $this->name($name);

        if ($this->context->count()) {
            return $name ? \implode(ProvidesName::NAMESPACE_SEPARATOR, [$this->current(), $name]) : '';
        }

        return $name;
    }

    /**
     * @param string $name
     * @return string
     */
    private function name(string $name): string
    {
        return \trim($name, ProvidesName::NAMESPACE_SEPARATOR);
    }

    /**
     * @return string
     */
    public function current(): string
    {
        return $this->context->count() > 0 ? $this->context->top() : '';
    }

    /**
     * @param ProvidesContext $record
     */
    public function push(ProvidesContext $record): void
    {
        $context = $record->atRoot() ? $record->getContext() : $this->mergeContext($record);

        $this->context->push($context);
    }

    /**
     * @param ProvidesContext $record
     * @return string
     */
    private function mergeContext(ProvidesContext $record): string
    {
        return $this->merge($record->getContext());
    }

    /**
     * @param ProvidesContext $context
     */
    public function complete(ProvidesContext $context): void
    {
        if (! $context->shouldRollback()) {
            return;
        }

        if ($this->context->count() === 0) {
            throw new LossOfStackException('Context stack data lost during transaction closing', $this->stack);
        }

        $this->context->pop();
    }
}
