<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\System;

use Railt\SDL\Compiler\Component\ContextComponent;
use Railt\SDL\Compiler\Context\LocalContextInterface;
use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Class CreateContextSystem
 */
class CreateContextSystem extends System
{
    /**
     * @param RecordInterface $record
     */
    public function provide(RecordInterface $record): void
    {
        $this->when($record)
            ->contains(ContextComponent::class)
            ->then(function (ContextComponent $provider) use ($record): void {
                $context = $provider->getContext();

                \assert($context instanceof LocalContextInterface);

                $record->getContext()->global()->push($context);
            });
    }
}
