<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\SDL\Compiler\Component\ComponentInterface;
use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Class Comparator
 */
class Comparator
{
    /**
     * @var RecordInterface
     */
    private $record;

    /**
     * @var bool
     */
    private $shouldInvoke = true;

    /**
     * @var array|ComponentInterface[]
     */
    private $provides = [];

    /**
     * Comparator constructor.
     * @param RecordInterface $record
     */
    public function __construct(RecordInterface $record)
    {
        $this->record = $record;
    }

    /**
     * @param string $component
     * @param \Closure|null $and
     * @return Comparator
     */
    public function contains(string $component, \Closure $and = null): Comparator
    {
        if ($this->shouldInvoke) {
            if ($this->record->has($component)) {
                $instance = $this->record->get($component);

                if ($and === null || $and($instance)) {
                    $this->provides[] = $instance;

                    return $this;
                }
            }

            $this->shouldInvoke = false;
        }

        return $this;
    }

    /**
     * @param \Closure $fn
     * @return Comparator
     */
    public function then(\Closure $fn): Comparator
    {
        if ($this->shouldInvoke) {
            $fn(...$this->provides);
        }

        return $this;
    }

    /**
     * @param \Closure $fn
     * @return Comparator
     */
    public function otherwise(\Closure $fn): Comparator
    {
        if (! $this->shouldInvoke) {
            $fn(...$this->provides);
        }

        return $this;
    }
}
