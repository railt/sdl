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
use Railt\SDL\Compiler\Record\RecordInterface;

/**
 * Class CompleteContextSystem
 */
class CompleteContextSystem extends System
{
    /**
     * @param RecordInterface $record
     */
    public function provide(RecordInterface $record): void
    {
        $this->when($record)
            ->contains(ContextComponent::class, function (ContextComponent $provider) {
                return $provider->shouldRollback();
            })
            ->then(function (ContextComponent $provider) {
                $provider->getContext()->global()->pop();
            });
    }
}
