<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\SDL\Compiler\Component\RelationsComponent;
use Railt\SDL\Compiler\Record\RecordInterface;
use Railt\SDL\Stack\CallStack;
use Railt\SDL\Stack\CallStackInterface;

/**
 * Class LinkerSystem
 */
class LinkerSystem extends System
{
    /**
     * @var CallStackInterface|CallStack
     */
    private $stack;

    /**
     * LinkerSystem constructor.
     * @param CallStack $stack
     */
    public function __construct(CallStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @param RecordInterface $record
     */
    public function provide(RecordInterface $record): void
    {
        $this->when($record)
            ->contains(RelationsComponent::class)
            ->then(function (RelationsComponent $relations) use ($record): void {
                foreach ($relations->getRelations() as $relation) {
                    $message = '-> fetch ' . $relation->getName();
                    $this->stack->push($relations->getContext()->getFile(), $relation->getPosition(), $message);

                    $relations->getContext()->getTypes()->fetch($relation->getName());

                    $this->stack->pop();
                }
            });
    }
}
