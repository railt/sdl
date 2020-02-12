<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Highlight;

use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Position\PositionInterface;

/**
 * Interface HighlightInterface
 */
interface HighlightInterface
{
    /**
     * @param ReadableInterface $source
     * @param PositionInterface $position
     * @return string
     */
    public function getCodeLine(ReadableInterface $source, PositionInterface $position): string;

    /**
     * @param ReadableInterface $source
     * @param PositionInterface $from
     * @param PositionInterface|null $to
     * @return string
     */
    public function render(ReadableInterface $source, PositionInterface $from, PositionInterface $to = null): string;
}
