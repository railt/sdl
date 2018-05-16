<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\SDL\Compiler\Component\VisibilityComponent;
use Railt\SDL\Compiler\Context\ContextInterface;
use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Class TypeRegisterSystem
 */
class TypeRegisterSystem extends System
{
    /**
     * @param RecordInterface $record
     */
    public function provide(RecordInterface $record): void
    {
        $this->context($record)->getTypes()->push($record);
    }

    /**
     * @param RecordInterface $record
     * @return ContextInterface
     */
    private function context(RecordInterface $record): ContextInterface
    {
        if ($this->isPublic($record)) {
            return $record->getContext()->global();
        }

        return $record->getContext();
    }

    /**
     * @param RecordInterface $record
     * @return bool
     */
    private function isPublic(RecordInterface $record): bool
    {
        return (bool)$this->when($record)
            ->contains(VisibilityComponent::class)
            ->then(function (VisibilityComponent $provider): bool {
                return $provider->isPublic();
            }, true);
    }
}
