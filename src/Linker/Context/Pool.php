<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker\Context;

use Railt\SDL\Linker\Record\ProvidesContext;
use Railt\SDL\Linker\Record\ProvidesName;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class Pool
 */
class Pool
{
    /**
     * @var \SplStack
     */
    private $pool;

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * Stack constructor.
     * @param CallStackInterface $stack
     */
    public function __construct(CallStackInterface $stack)
    {
        $this->pool = new \SplStack();
        $this->stack = $stack;
    }

    /**
     * @param string $name
     * @return string
     */
    public function concat(string $name): string
    {
        if ($name === ProvidesName::NAMESPACE_SEPARATOR) {
            return '';
        }

        $name = \trim($name, ProvidesName::NAMESPACE_SEPARATOR);

        if (! $name) {
            return $this->current();
        }

        if (! $this->current()) {
            return $name;
        }

        return \implode(ProvidesName::NAMESPACE_SEPARATOR, [$this->current(), $name]);
    }

    /**
     * @return string
     */
    public function current(): string
    {
        return $this->pool->count() ? $this->pool->top() : '';
    }

    /**
     * @param ProvidesName $record
     * @return string
     */
    public function name(ProvidesName $record): string
    {
        return $record->atRoot() ? $record->getName() : $this->concat($record->getName());
    }

    /**
     * @param ProvidesName $record
     */
    public function push(ProvidesName $record): void
    {
        $context = $record->atRoot()
            ? $record->getName()
            : $this->concat($record->getName());

        //
        $shouldReplace = $record instanceof ProvidesContext && ! $record->shouldRollback();

        if ($shouldReplace && $this->pool->count()) {
            $this->pool->pop();
        }

        $this->pool->push($context);
    }

    /**
     * @param ProvidesContext $record
     */
    public function complete(ProvidesContext $record): void
    {
        if ($record->shouldRollback()) {
            $this->pool->pop();
        }
    }
}
